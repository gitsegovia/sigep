<?php

class ReporteFormulacionGob2014Controller extends AppController{

	var $name = 'reporte_formulacion_gob2014';

    var $uses = array('cfpd01_formulacion', 'cfpd01_ano_partida', 'v_2014_categoria_inst',
                      'v_2014_rc_par_inst', 'v_2014_rc_par_dep', 'cfpd05', 'cfpd02_sector', 'cfpd02_programa',
                      'cfpd08_ident_inst', 'cfpd08_ident_dir_inst','cfpd08_ident_clp', 'v_cnmd04_ocupacion',
                      'cfpd11_pol_pres_finan', 'cfpd03','v_2014_ingresos_inst','v_2014_ingresos_dep',
                      'v_2014_rc_sp_inst','v_2014_rc_sp_dep', 'v_2014_rc_spsp_inst','v_2014_rc_spsp_dep',
                      'v_2014_rch_tg_inst','v_2014_rch_tg_dep','v_2014_rch_es_inst','v_2014_rch_es_dep',
                      'v_2014_gastos_inv','v_2014_metas','v_2014_dpsp','v_programas_metas',
                      'v_2014_rch_tg_spsp_dep','v_2014_rch_tg_spsp_inst', 'v_cfpd97_reporte2_inst_spsp_final',
                      'v_2014_rch_es_spsp_dep','v_2014_rch_es_spsp_inst','cfpd02_sub_prog',
                      'v_2014_categoria_dep','v_balance_ejecucion', 'v_forma_2126_depv2','v_forma_2126_instv2',
                      'v_2014_rch_tg_entes');


	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');


	function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
	}//fin checksession


function limpia_menu(){

    	 echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
               </script>';
    }



    function beforeFilter(){
		$this->checkSession();
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

    function SQLCA_consolidado($pre=null){
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1 && $this->verifica_SS(5)==1){
            $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
         }else{
          $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
          if($pre==2 && $this->verifica_SS(5)==1){
           $sql_re .= "cod_dep IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,1020,1014,1015,1022,1011,1007,1023,1016,1004,1012,1003,1009,1000,1005,1018,1010,1001,1019,1008) "; 
          }else{
            $sql_re .= "cod_dep=".$_SESSION['cod_dep_reporte_consolidado']." ";            
          }
         }

         return $sql_re;
    }//fin funcion SQLCA

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




function forma_2000($var=null){

             $cod_presi = $this->Session->read('SScodpresi');
			 $cod_entidad = $this->Session->read('SScodentidad');
			 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			 $cod_inst = $this->Session->read('SScodinst');
			 $cod_dep = $this->Session->read('SScoddep');
			 $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

             $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);

	        $dato = null;
	        foreach($year as $year){$dato = $year['cfpd01_formulacion']['ano_formular'];}
            if(!empty($dato)){$this->set('year', $dato);}else{$this->set('year', '');}

        if($var==null){
                $this->layout = 'ajax';
                $this->limpia_menu();
            	$this->set('ir', 'no');

         }else{
         	 $ano = $this->data['cfpp08']['ano'];
             $this->layout = 'pdf';
             $cod_presi = $this->Session->read('SScodpresi');
			 $cod_entidad = $this->Session->read('SScodentidad');
			 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			 $cod_inst = $this->Session->read('SScodinst');
			 $cod_dep = $this->Session->read('SScoddep');

  	     	$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
            $titulo_a = "DIRECCIÓN ESTADAL DE PRESUPUESTO";

            	$datos_inst=$this->cfpd08_ident_inst->findAll($sql.' and ejercicio_fiscal='.$ano);
            	$datos_directivos=$this->cfpd08_ident_dir_inst->findAll($sql.' and ejercicio_fiscal='.$ano);
            	$datos_conce_plan=$this->cfpd08_ident_clp->findAll($sql.' and ejercicio_fiscal='.$ano);

            $_SESSION['ejercicio'] = $ano;
            $this->set('datos_inst',$datos_inst);
            $this->set('datos_directivos',$datos_directivos);
            $this->set('datos_conce_plan',$datos_conce_plan);
            $this->set('titulo_a', $titulo_a);

         }//fin else


            $this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin de función forma:2000




function forma_2001($var=null){

             $cod_presi = $this->Session->read('SScodpresi');
			 $cod_entidad = $this->Session->read('SScodentidad');
			 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			 $cod_inst = $this->Session->read('SScodinst');
			 $cod_dep = $this->Session->read('SScoddep');
			 $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

             $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);

	        $dato = null;
	        foreach($year as $year){$dato = $year['cfpd01_formulacion']['ano_formular'];}
            if(!empty($dato)){$this->set('year', $dato);}else{$this->set('year', '');}

        if($var==null){
                $this->layout = 'ajax';
                $this->limpia_menu();
            	$this->set('ir', 'no');

         }else{
         	 $ano = $this->data['cfpp08']['ano'];
             $this->layout = 'pdf';
             $cod_presi = $this->Session->read('SScodpresi');
			 $cod_entidad = $this->Session->read('SScodentidad');
			 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
			 $cod_inst = $this->Session->read('SScodinst');
			 $cod_dep = $this->Session->read('SScoddep');

  	     	$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

            $datos_inst=$this->cfpd11_pol_pres_finan->findAll($sql.' and ano='.$ano);

	        $_SESSION['ejercicio'] = $ano;
            $this->set('datos_inst',$datos_inst);


         }//fin else


            $this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin de función forma:2001




function forma_2002($year=null){
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

			$sql='';

	if(!$year){

		    $this->layout = "ajax";
		    $this->set('ir', 'no');

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
          if($consolidado==2){
          	$modelo = 'v_2014_ingresos_dep';
  	     	$sql = $this->SQLCA_consolidado($consolidado);
  	}else if($consolidado==1){
  		    $modelo = 'v_2014_ingresos_inst';
  		    $sql = $this->SQLCA_consolidado($consolidado);
  	}

			if(!empty($ano)){
				$condicion = $sql.' and ano='.$ano;
			}else{
				$condicion = $sql;
			}

if($consolidado==2){
	$DATOS_res = $this->$modelo->findAll($condicion, null, null, null);
}else{
    $DATOS_res = $this->$modelo->findAll($condicion, null, null, null);
}

	$_SESSION['ejercicio'] = $ano;
	$this->set('modelo', $modelo);
    $this->set('datos', $DATOS_res);

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function forma_2002





	/**********************************************************
	 **  FUNCION: forma_2003                                 **
	 **    FORMA: 2003                                       **
	 **     ENTE: GOBERNACION ESTATAL                        **
	 **  REPORTE: INDICE DE CATEGORIAS PROGRAMATICAS         **
	 **  CONSOLIDADO POR: INSTITUCION / DEPENDENCIA  +  AÑO  **
	 **********************************************************/



	function forma_2003($opcion = null){
		if($opcion=='si'){

		    $this->layout = "ajax";
		    $this->set('ir', 'pdf');
		    $this->set('opcion', 1);

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

	$ano_actual = date("Y");
	for($minCount = 2007; $minCount < 2030; $minCount++) {
	    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
	}

	if($ano_actual == $anos[sprintf('%02d', $minCount-1)]){
		$ano_actual_fin = $ano_actual+30;
		for($minCount = $ano_actual; $minCount < $ano_actual_fin; $minCount++) {
	    	$anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
		}
	}

	    $this->set('anos', $anos);
	    $this->set('ano_formulacion', $dato);


		}else if($opcion=='pdf'){

			$this->layout = "pdf";
			$this->set('opcion', 2);
			$sql = '';
			$ano = $this->data['v_2014_categoria']['ano'];
			$_SESSION['ejercicio'] = $ano;
			$consolidado = 2;
			if(isset($this->data['v_2014_categoria']['consolidacion'])){
				$consolidado = (int) $this->data['v_2014_categoria']['consolidacion'];
			}

			if($consolidado==2){
				$modelo = 'v_2014_categoria_dep';
				$sql = $this->SQLCA_consolidado($consolidado);
			}else if($consolidado==1){
				$modelo = 'v_2014_categoria_inst';
				$sql = $this->SQLCA_consolidado($consolidado);
			}

			if(!empty($ano)){
				$condicion = $sql.' and ano='.$ano;
			}else{
				$condicion = $sql;
			}

			if($consolidado==1){
				$datos = $this->$modelo->findAll($condicion, 'ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, denominacion, unidad_ejecutora', 'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra ASC', null, null, null);
			}else{
				$datos = $this->$modelo->findAll($condicion, 'ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, denominacion, unidad_ejecutora', 'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra ASC', null, null, null);
			}
			$this->set('modelo', $modelo);
			$this->set('datos', $datos);
		}//fin else

		$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	} // Fin Function forma_2003





	function forma_2004($opcion = null){
		if($opcion=='si'){

		    $this->layout = "ajax";
		    $this->set('ir', 'pdf');
		    $this->set('opcion', 1);

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

	$ano_actual = date("Y");
	for($minCount = 2007; $minCount < 2030; $minCount++) {
	    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
	}

	if($ano_actual == $anos[sprintf('%02d', $minCount-1)]){
		$ano_actual_fin = $ano_actual+30;
		for($minCount = $ano_actual; $minCount < $ano_actual_fin; $minCount++) {
	    	$anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
		}
	}

	    $this->set('anos', $anos);
	    $this->set('ano_formulacion', $dato);


		}else if($opcion=='pdf'){

			$this->layout = "pdf";
			$this->set('opcion', 2);
			$sql = '';
			$ano = $this->data['v_2014_dato']['ano'];
			$_SESSION['ejercicio'] = $ano;
			$consolidado = 2;
			if(isset($this->data['v_2014_dato']['consolidacion'])){
				$consolidado = (int) $this->data['v_2014_dato']['consolidacion'];
			}

			if($consolidado==2){
				$modelo = 'v_2014_rc_sp_dep';
				$sql = $this->SQLCA_consolidado($consolidado);
			}else if($consolidado==1){
				$modelo = 'v_2014_rc_sp_inst';
				$sql = $this->SQLCA_consolidado($consolidado);
			}

			if(!empty($ano)){
				$condicion = $sql.' and ano='.$ano;
			}else{
				$condicion = $sql;
			}

			$datos = $this->$modelo->findAll($condicion, 'ano, cod_sector, cod_programa, ingresos_propios, situado_estadal, fci, otras_fuentes_estadal, total, denominacion');
			$this->set('modelo', $modelo);
			$this->set('datos', $datos);
		}//fin else

		$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	} // Fin Function forma_2004



	function forma_2004_b($opcion = null){
		if($opcion=='si'){

		    $this->layout = "ajax";
		    $this->set('ir', 'pdf');
		    $this->set('opcion', 1);

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

	$ano_actual = date("Y");
	for($minCount = 2007; $minCount < 2030; $minCount++) {
	    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
	}

	if($ano_actual == $anos[sprintf('%02d', $minCount-1)]){
		$ano_actual_fin = $ano_actual+30;
		for($minCount = $ano_actual; $minCount < $ano_actual_fin; $minCount++) {
	    	$anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
		}
	}

	    $this->set('anos', $anos);
	    $this->set('ano_formulacion', $dato);


		}else if($opcion=='pdf'){

			$this->layout = "pdf";
			$this->set('opcion', 2);
			$sql = '';
			$ano = $this->data['v_2014_dato']['ano'];
			$_SESSION['ejercicio'] = $ano;
			$consolidado = 2;
			if(isset($this->data['v_2014_dato']['consolidacion'])){
				$consolidado = (int) $this->data['v_2014_dato']['consolidacion'];
			}

			if($consolidado==2){
				$modelo = 'v_2014_rc_spsp_dep';
				$sql = $this->SQLCA_consolidado($consolidado);
			}else if($consolidado==1){
				$modelo = 'v_2014_rc_spsp_inst';
				$sql = $this->SQLCA_consolidado($consolidado);
			}

			if(!empty($ano)){
				$condicion = $sql.' and ano='.$ano;
			}else{
				$condicion = $sql;
			}

			$datos = $this->$modelo->findAll($condicion, 'ano, cod_sector, cod_programa, cod_sub_prog, ingresos_propios, situado_estadal, fci, otras_fuentes_estadal, total, denominacion');
			$this->set('modelo', $modelo);
			$this->set('datos', $datos);
		}//fin else

		$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	} // Fin Function forma_2004_B




	/**********************************************************
	 **  FUNCION: forma_2005                                 **
	 **    FORMA: 2005                                       **
	 **     ENTE: GOBERNACION ESTATAL                        **
	 **  REPORTE: RESUMEN DE LOS CREDITOS PRESUPUESTARIOS    **
	 **  CONSOLIDADO POR: INSTITUCION / DEPENDENCIA  +  ANO  **
	 **********************************************************/

	function forma_2005($opcion = null){
		if($opcion=='si'){

		    $this->layout = "ajax";
		    $this->set('ir', 'pdf');
		    $this->set('opcion', 1);

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

	$ano_actual = date("Y");
	for($minCount = 2007; $minCount < 2030; $minCount++) {
	    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
	}

	if($ano_actual == $anos[sprintf('%02d', $minCount-1)]){
		$ano_actual_fin = $ano_actual+30;
		for($minCount = $ano_actual; $minCount < $ano_actual_fin; $minCount++) {
	    	$anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
		}
	}

	    $this->set('anos', $anos);
	    $this->set('ano_formulacion', $dato);


		}else if($opcion=='pdf'){

			$this->layout = "pdf";
			$this->set('opcion', 2);
			$sql = '';
			$ano = $this->data['v_2014_dato']['ano'];
			$_SESSION['ejercicio'] = $ano;
			$consolidado = 2;
			if(isset($this->data['v_2014_dato']['consolidacion'])){
				$consolidado = (int) $this->data['v_2014_dato']['consolidacion'];
			}

			if($consolidado==2){
				$modelo = 'v_2014_rc_par_dep';
				$sql = $this->SQLCA_consolidado($consolidado);
			}else if($consolidado==1){
				$modelo = 'v_2014_rc_par_inst';
				$sql = $this->SQLCA_consolidado($consolidado);
			}

			if(!empty($ano)){
				$condicion = $sql.' and ano='.$ano;
			}else{
				$condicion = $sql;
			}

			$datos = $this->$modelo->findAll($condicion, 'ano, cod_partida, ingresos_propios, situado_estadal, fci, otras_fuentes_estadal, total, denominacion');
			$this->set('modelo', $modelo);
			$this->set('datos', $datos);
		}//fin else

		$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	} // Fin Function forma_2005




	/**********************************************************
	 **  FUNCION: forma_2006                                 **
	 **    FORMA: 2006                                       **
	 **     ENTE: ALCALDIA MUNICIPAL                         **
	 **  REPORTE: RESUMEN DE LOS CREDITOS PRESUPUESTARIOS    **
	 **  CONSOLIDADO POR: INSTITUCION / DEPENDENCIA  +  ANO  **
	 **********************************************************/

	function forma_2006($opcion = null){
		if($opcion=='si'){

		    $this->layout = "ajax";
		    $this->set('ir', 'pdf');
		    $this->set('opcion', 1);

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

	$ano_actual = date("Y");
	for($minCount = 2007; $minCount < 2030; $minCount++) {
	    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
	}

	if($ano_actual == $anos[sprintf('%02d', $minCount-1)]){
		$ano_actual_fin = $ano_actual+30;
		for($minCount = $ano_actual; $minCount < $ano_actual_fin; $minCount++) {
	    	$anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
		}
	}

	    $this->set('anos', $anos);
	    $this->set('ano_formulacion', $dato);


		}else if($opcion=='pdf'){

			$this->layout = "pdf";
			$this->set('opcion', 2);
			$sql = '';
			$ano = $this->data['v_2014_dato']['ano'];
			$_SESSION['ejercicio'] = $ano;
			$consolidado = 2;
			if(isset($this->data['v_2014_dato']['consolidacion'])){
				$consolidado = (int) $this->data['v_2014_dato']['consolidacion'];
			}

			if($consolidado==2){
				$modelo = 'v_2014_rc_par_sect_dep';
				$sql = $this->SQLCA_consolidado($consolidado);
			}else if($consolidado==1){
				$modelo = 'v_2014_rc_par_sect_inst';
				$sql = $this->SQLCA_consolidado($consolidado);
			}

			if(!empty($ano)){
				$condicion = $sql.' and ano='.$ano;
			}else{
				$condicion = $sql;
			}

			$sector = $this->cfpd02_sector->findAll($condicion,null,'cod_sector ASC');
			$datos = $this->cfpd05->execute("SELECT * FROM $modelo WHERE ".$condicion.";");
		    $this->set('sector', $sector);
		    $this->set('datos', $datos);
		}//fin else

		$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	} // Fin Function forma_2006





function forma_2007($year=null) {
set_time_limit(0);
	$sql='';

  if(!$year){
    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$_SESSION['ejercicio'] = $ano;
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$i  = 0;
	$xi = 0;
	$j  = 0;
	$k  = 0;
	$l  = 0;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_tg_dep";

  	}else if($consolidado==1){
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_tg_inst";

  	}
            $DATOS_res = $this->$modelo->findAll($sql."and cod_nivel_i<=3");
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $this->set('ocupacion',$this->v_cnmd04_ocupacion->findAll("cod_nivel_i<=3",null,"cod_nivel_i,cod_nivel_ii ASC"));

  }
  $this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin funcion forma_2007





// FUNCION QUITADA DE LOS FORMATOS
/*
function forma_2008 ($year=null) {
set_time_limit(0);
	$sql='';
  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$_SESSION['ejercicio'] = $ano;

  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_es_dep";
  	}else if($consolidado==1){
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_es_inst";
  	}

			$tc=$sql." and cod_nivel_i=1 AND (clasificacion_personal = 1 OR clasificacion_personal = 3 OR clasificacion_personal = 4 OR clasificacion_personal = 5 OR clasificacion_personal = 6 OR clasificacion_personal = 7 OR clasificacion_personal = 9 OR clasificacion_personal = 11 OR clasificacion_personal = 12 OR clasificacion_personal = 13 OR clasificacion_personal = 14 OR clasificacion_personal = 17 OR clasificacion_personal = 18)";
            $datos_nivel_tc= $this->$modelo->execute("select cod_nivel_i, sum(empleados) as empleados, sum(monto_empleados) as monto_empleados, sum(compensaciones_empleados) as compensaciones_empleados, escala, grupo, desde_monto, hasta_monto  FROM $modelo WHERE $tc group by cod_nivel_i, escala, grupo, desde_monto, hasta_monto order by cod_nivel_i, escala, grupo ASC");
            $this->set('datos_nivel_tc', $datos_nivel_tc);
            $tp=$sql." and cod_nivel_i=2 AND (clasificacion_personal = 1 OR clasificacion_personal = 3 OR clasificacion_personal = 4 OR clasificacion_personal = 5 OR clasificacion_personal = 6 OR clasificacion_personal = 7 OR clasificacion_personal = 9 OR clasificacion_personal = 11 OR clasificacion_personal = 12 OR clasificacion_personal = 13 OR clasificacion_personal = 14 OR clasificacion_personal = 17 OR clasificacion_personal = 18)";
            $datos_nivel_tp= $this->$modelo->execute("select cod_nivel_i, sum(empleados) as empleados, sum(monto_empleados) as monto_empleados, sum(compensaciones_empleados) as compensaciones_empleados, escala, grupo, desde_monto, hasta_monto  FROM $modelo WHERE $tp group by cod_nivel_i, escala, grupo, desde_monto, hasta_monto order by cod_nivel_i, escala, grupo ASC");
            $this->set('datos_nivel_tp', $datos_nivel_tp);
            $pc=$sql." and cod_nivel_i=3 AND (clasificacion_personal = 1 OR clasificacion_personal = 3 OR clasificacion_personal = 4 OR clasificacion_personal = 5 OR clasificacion_personal = 6 OR clasificacion_personal = 7 OR clasificacion_personal = 9 OR clasificacion_personal = 11 OR clasificacion_personal = 12 OR clasificacion_personal = 13 OR clasificacion_personal = 14 OR clasificacion_personal = 17 OR clasificacion_personal = 18)";
            $datos_nivel_pc= $this->$modelo->execute("select cod_nivel_i, sum(empleados) as empleados, sum(monto_empleados) as monto_empleados, sum(compensaciones_empleados) as compensaciones_empleados, escala, grupo, desde_monto, hasta_monto  FROM $modelo WHERE $pc group by cod_nivel_i, escala, grupo, desde_monto, hasta_monto order by cod_nivel_i, escala, grupo ASC");
            $this->set('datos_nivel_pc', $datos_nivel_pc);

            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
  }

$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin funcion forma_2008



function forma_2009 ($year=null) {
set_time_limit(0);
	$sql='';
  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$_SESSION['ejercicio'] = $ano;

  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_es_dep";
  	}else if($consolidado==1){
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_es_inst";
  	}

			$tc=$sql." and cod_nivel_i=1 AND (clasificacion_personal = 2 OR clasificacion_personal = 8 OR clasificacion_personal = 10 OR clasificacion_personal = 15 OR clasificacion_personal = 16)";
            $datos_nivel_tc= $this->$modelo->execute("select cod_nivel_i, sum(obreros) as obreros, sum(monto_obreros) as monto_obreros, sum(compensaciones_obreros) as compensaciones_obreros, escala, grupo, desde_monto, hasta_monto  FROM $modelo WHERE $tc group by cod_nivel_i, escala, grupo, desde_monto, hasta_monto order by cod_nivel_i, escala, grupo ASC");
            $this->set('datos_nivel_tc', $datos_nivel_tc);
            $tp=$sql." and cod_nivel_i=2 AND (clasificacion_personal = 2 OR clasificacion_personal = 8 OR clasificacion_personal = 10 OR clasificacion_personal = 15 OR clasificacion_personal = 16)";
            $datos_nivel_tp= $this->$modelo->execute("select cod_nivel_i, sum(obreros) as obreros, sum(monto_obreros) as monto_obreros, sum(compensaciones_obreros) as compensaciones_obreros, escala, grupo, desde_monto, hasta_monto  FROM $modelo WHERE $tp group by cod_nivel_i, escala, grupo, desde_monto, hasta_monto order by cod_nivel_i, escala, grupo ASC");
            $this->set('datos_nivel_tp', $datos_nivel_tp);
            $pc=$sql." and cod_nivel_i=3 AND (clasificacion_personal = 2 OR clasificacion_personal = 8 OR clasificacion_personal = 10 OR clasificacion_personal = 15 OR clasificacion_personal = 16)";
            $datos_nivel_pc= $this->$modelo->execute("select cod_nivel_i, sum(obreros) as obreros, sum(monto_obreros) as monto_obreros, sum(compensaciones_obreros) as compensaciones_obreros, escala, grupo, desde_monto, hasta_monto  FROM $modelo WHERE $pc group by cod_nivel_i, escala, grupo, desde_monto, hasta_monto order by cod_nivel_i, escala, grupo ASC");
            $this->set('datos_nivel_pc', $datos_nivel_pc);


            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin funcion forma_2009

*/


function forma_2010($year=null) {
set_time_limit(0);
	$sql='';

  if(!$year){
    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$_SESSION['ejercicio'] = $ano;
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$i  = 0;
	$xi = 0;
	$j  = 0;
	$k  = 0;
	$l  = 0;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_tg_dep";

  	}else if($consolidado==1){
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_tg_inst";

  	}
            $DATOS_res = $this->$modelo->findAll($sql."AND (cod_nivel_i>3 AND cod_nivel_i<6)");
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $this->set('ocupacion',$this->v_cnmd04_ocupacion->findAll("(cod_nivel_i>3 AND cod_nivel_i<6)",null,"cod_nivel_i,cod_nivel_ii ASC"));


  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin funcion forma_2010





function forma_2011($year=null){
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

                        $xx=$this->cfpd05->execute("SELECT * FROM v_2014_gastos_inv L  ".$condicion." ORDER BY L.cod_sector, L.cod_programa, L.cod_partida,L.cod_generica,L.cod_especifica,L.cod_sub_espec ASC");

					    $this->set('datos', $xx);
					    $this->set('ejercicio', $ano);

    }//fin function

$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function



//quitada de los formatos
/*
function forma_2012($year=null){
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

						          if($consolidado==2){
						  	     	$sql = $this->SQLCA_consolidado($consolidado);
						  	}else if($consolidado==1){
						  		    $sql = $this->SQLCA_consolidado($consolidado);
						  	}

							$condicion= $sql.'and ano='.$ano;

            $DATOS_res = $this->v_2014_metas->findAll($condicion,null, null, null, null,null);

            $this->set('datos', $DATOS_res);
            $_SESSION['ejercicio'] = $ano;

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function 2012
*/



function forma_2013($opcion = null){
		if($opcion=='si'){

		    $this->layout = "ajax";
		    $this->set('ir', 'pdf');
		    $this->set('opcion', 1);

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

	$ano_actual = date("Y");
	for($minCount = 2007; $minCount < 2030; $minCount++) {
	    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
	}

	if($ano_actual == $anos[sprintf('%02d', $minCount-1)]){
		$ano_actual_fin = $ano_actual+30;
		for($minCount = $ano_actual; $minCount < $ano_actual_fin; $minCount++) {
	    	$anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
		}
	}

	    $this->set('anos', $anos);
	    $this->set('ano_formulacion', $dato);


		}else if($opcion=='pdf'){

			$this->layout = "pdf";
			$this->set('opcion', 2);
			$sql = '';
			$ano = $this->data['v_2014_dato']['ano'];
			$_SESSION['ejercicio'] = $ano;
			$consolidado = 2;
			if(isset($this->data['v_2014_dato']['consolidacion'])){
				$consolidado = (int) $this->data['v_2014_dato']['consolidacion'];
			}

			if($consolidado==2){
				$modelo = 'v_2014_transf_donac_dep';
				$sql = $this->SQLCA_consolidado($consolidado);
			}else if($consolidado==1){
				$modelo = 'v_2014_transf_donac_inst';
				$sql = $this->SQLCA_consolidado($consolidado);
			}

			if(!empty($ano)){
				$condicion = $sql.' and ano='.$ano;
			}else{
				$condicion = $sql;
			}

			$datos = $this->cfpd05->execute("SELECT * FROM $modelo WHERE ".$condicion.";");

		    $this->set('datos', $datos);
		    $this->set('modelo', $modelo);
		}//fin else

		$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	} // Fin Function forma_2013





function forma_2014($var1=null, $var2=null){
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
            $_SESSION['ejercicio'] = $ano;
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

 }

$this->set("opcion", $opcion);

}//fin forma_2014




function forma_2015($var1=null, $var2=null){
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

		$filtro = $sql." and a.cod_proyecto<>0 and a.ano=".$ano;
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

}//fin forma_2015





function forma_2016($year=null){

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

								    $condicion= $sql.'and cod_proyecto!=0 and ano='.$ano;

									$DATOS_res = $this->v_2014_metas->findAll($condicion,null, null, null, null,null);

									$this->set('datos', $DATOS_res);
									$this->set('year',  $ano);
									$_SESSION['ejercicio'] = $ano;
    }

$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin forma_2016





function forma_2017 ($year=null) {

set_time_limit(0);
//echo $year."   si";

	$sql='';

  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
	if($cod_dep!=1){
		$condicion.=" and cod_dep=$cod_dep";
	}

    $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$i  = 0;
	$xi = 0;
	$j  = 0;
	$k  = 0;
	$l  = 0;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
            $sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_tg_spsp_dep";
  	}else if($consolidado==1){
            $sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_tg_spsp_inst";
  	}
  	if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
         $cod_programa = isset($this->data['reporte']['cod_programa']) && !empty($this->data['reporte']['cod_programa']) ? " cod_programa=".$this->data['reporte']['cod_programa']:"2=2";
         $cod_sub_prog = isset($this->data['reporte']['cod_subprograma']) && !empty($this->data['reporte']['cod_subprograma']) ? " cod_sub_prog=".$this->data['reporte']['cod_subprograma']:"3=3";
  	     $sql .= " and ".$cod_sector ." and ".$cod_programa." and ".$cod_sub_prog;
  	}



  	        $DATOS_sectores= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog FROM $modelo WHERE $sql. AND cod_nivel_i<=3 group by cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog order by cod_sector,cod_programa,cod_sub_prog ASC");
            $DATOS_res = $this->$modelo->findAll($sql." AND cod_nivel_i<=3");
            $this->set('data_sectores', $DATOS_sectores);
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $_SESSION['ejercicio'] = $ano_formular;
            $this->set('ocupacion',$this->v_cnmd04_ocupacion->findAll("cod_nivel_i<=3",null,"cod_nivel_i,cod_nivel_ii ASC"));

  }

$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin funcion forma_2017





function forma_2018 ($year=null) {
set_time_limit(0);
	$sql='';
  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}

	        $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_es_spsp_dep";
  	}else if($consolidado==1){
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_es_spsp_inst";
  	}
  	if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
         $cod_programa = isset($this->data['reporte']['cod_programa']) && !empty($this->data['reporte']['cod_programa']) ? " cod_programa=".$this->data['reporte']['cod_programa']:"2=2";
         $cod_sub_prog = isset($this->data['reporte']['cod_subprograma']) && !empty($this->data['reporte']['cod_subprograma']) ? " cod_sub_prog=".$this->data['reporte']['cod_subprograma']:"3=3";
  	     $sql .= " and ".$cod_sector ." and ".$cod_programa." and ".$cod_sub_prog;
  	}

  	        $tc='';
  	        $tp='';
  	        $pc='';
  	        $DATOS_sectores_tc='';
  	        $DATOS_sectores_tp='';
  	        $DATOS_sectores_pc='';
  	        $tc=$sql." and cod_nivel_i=1 AND (clasificacion_personal = 1 OR clasificacion_personal = 3 OR clasificacion_personal = 4 OR clasificacion_personal = 5 OR clasificacion_personal = 6 OR clasificacion_personal = 7 OR clasificacion_personal = 9 OR clasificacion_personal = 11 OR clasificacion_personal = 12 OR clasificacion_personal = 13 OR clasificacion_personal = 14 OR clasificacion_personal = 17 OR clasificacion_personal = 18)";
  	        $DATOS_sectores_tc= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog FROM $modelo WHERE $tc group by cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog order by cod_sector,cod_programa,cod_sub_prog ASC");
            $this->set('data_sectores_tc', $DATOS_sectores_tc);
  	        $tp=$sql." and cod_nivel_i=2 AND (clasificacion_personal = 1 OR clasificacion_personal = 3 OR clasificacion_personal = 4 OR clasificacion_personal = 5 OR clasificacion_personal = 6 OR clasificacion_personal = 7 OR clasificacion_personal = 9 OR clasificacion_personal = 11 OR clasificacion_personal = 12 OR clasificacion_personal = 13 OR clasificacion_personal = 14 OR clasificacion_personal = 17 OR clasificacion_personal = 18)";
  	        $DATOS_sectores_tp= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog FROM $modelo WHERE $tp group by cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog order by cod_sector,cod_programa,cod_sub_prog ASC");
            $this->set('data_sectores_tp', $DATOS_sectores_tp);
            $pc=$sql." and cod_nivel_i=3 AND (clasificacion_personal = 1 OR clasificacion_personal = 3 OR clasificacion_personal = 4 OR clasificacion_personal = 5 OR clasificacion_personal = 6 OR clasificacion_personal = 7 OR clasificacion_personal = 9 OR clasificacion_personal = 11 OR clasificacion_personal = 12 OR clasificacion_personal = 13 OR clasificacion_personal = 14 OR clasificacion_personal = 17 OR clasificacion_personal = 18)";
  	        $DATOS_sectores_pc= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog FROM $modelo WHERE $pc group by cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog order by cod_sector,cod_programa,cod_sub_prog ASC");
            $this->set('data_sectores_pc', $DATOS_sectores_pc);

            $datos_nivel_tc= $this->$modelo->execute("select cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, sum(empleados) as empleados, sum(monto_empleados) as monto_empleados, sum(compensaciones_empleados) as compensaciones_empleados, escala, grupo, desde_monto, hasta_monto  FROM $modelo WHERE $tc group by cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, escala, grupo, desde_monto, hasta_monto order by cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, escala, grupo ASC");
            $this->set('datos_nivel_tc', $datos_nivel_tc);
            $datos_nivel_tp= $this->$modelo->execute("select cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, sum(empleados) as empleados, sum(monto_empleados) as monto_empleados, sum(compensaciones_empleados) as compensaciones_empleados, escala, grupo, desde_monto, hasta_monto  FROM $modelo WHERE $tp group by cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, escala, grupo, desde_monto, hasta_monto order by cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, escala, grupo ASC");
            $this->set('datos_nivel_tp', $datos_nivel_tp);
            $datos_nivel_pc= $this->$modelo->execute("select cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, sum(empleados) as empleados, sum(monto_empleados) as monto_empleados, sum(compensaciones_empleados) as compensaciones_empleados, escala, grupo, desde_monto, hasta_monto  FROM $modelo WHERE $pc group by cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, escala, grupo, desde_monto, hasta_monto order by cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, escala, grupo ASC");
            $this->set('datos_nivel_pc', $datos_nivel_pc);

            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $_SESSION['ejercicio'] = $ano_formular;
  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin funcion forma_2018






function forma_2019 ($year=null) {
set_time_limit(0);
	$sql='';
  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}

	        $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_es_spsp_dep";
  	}else if($consolidado==1){
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_es_spsp_inst";
  	}
  	if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
         $cod_programa = isset($this->data['reporte']['cod_programa']) && !empty($this->data['reporte']['cod_programa']) ? " cod_programa=".$this->data['reporte']['cod_programa']:"2=2";
         $cod_sub_prog = isset($this->data['reporte']['cod_subprograma']) && !empty($this->data['reporte']['cod_subprograma']) ? " cod_sub_prog=".$this->data['reporte']['cod_subprograma']:"3=3";
  	     $sql .= " and ".$cod_sector ." and ".$cod_programa." and ".$cod_sub_prog;
  	}

  	        $tc='';
  	        $tp='';
  	        $pc='';
  	        $DATOS_sectores_tc='';
  	        $DATOS_sectores_tp='';
  	        $DATOS_sectores_pc='';
  	        $tc=$sql." and cod_nivel_i=1 AND (clasificacion_personal = 2 OR clasificacion_personal = 8 OR clasificacion_personal = 10 OR clasificacion_personal = 15 OR clasificacion_personal = 16)";
  	        $DATOS_sectores_tc= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog FROM $modelo WHERE $tc group by cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog order by cod_sector,cod_programa,cod_sub_prog ASC");
            $this->set('data_sectores_tc', $DATOS_sectores_tc);
  	        $tp=$sql." and cod_nivel_i=2 AND (clasificacion_personal = 2 OR clasificacion_personal = 8 OR clasificacion_personal = 10 OR clasificacion_personal = 15 OR clasificacion_personal = 16)";
  	        $DATOS_sectores_tp= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog FROM $modelo WHERE $tp group by cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog order by cod_sector,cod_programa,cod_sub_prog ASC");
            $this->set('data_sectores_tp', $DATOS_sectores_tp);
            $pc=$sql." and cod_nivel_i=3 AND (clasificacion_personal = 2 OR clasificacion_personal = 8 OR clasificacion_personal = 10 OR clasificacion_personal = 15 OR clasificacion_personal = 16)";
  	        $DATOS_sectores_pc= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog FROM $modelo WHERE $pc group by cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog order by cod_sector,cod_programa,cod_sub_prog ASC");
            $this->set('data_sectores_pc', $DATOS_sectores_pc);

            $datos_nivel_tc= $this->$modelo->execute("select cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, sum(obreros) as obreros, sum(monto_obreros) as monto_obreros, sum(compensaciones_obreros) as compensaciones_obreros, escala, grupo, desde_monto, hasta_monto  FROM $modelo WHERE $tc group by cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, escala, grupo, desde_monto, hasta_monto order by cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, escala, grupo ASC");
            $this->set('datos_nivel_tc', $datos_nivel_tc);
            $datos_nivel_tp= $this->$modelo->execute("select cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, sum(obreros) as obreros, sum(monto_obreros) as monto_obreros, sum(compensaciones_obreros) as compensaciones_obreros, escala, grupo, desde_monto, hasta_monto  FROM $modelo WHERE $tp group by cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, escala, grupo, desde_monto, hasta_monto order by cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, escala, grupo ASC");
            $this->set('datos_nivel_tp', $datos_nivel_tp);
            $datos_nivel_pc= $this->$modelo->execute("select cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, sum(obreros) as obreros, sum(monto_obreros) as monto_obreros, sum(compensaciones_obreros) as compensaciones_obreros, escala, grupo, desde_monto, hasta_monto  FROM $modelo WHERE $pc group by cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, escala, grupo, desde_monto, hasta_monto order by cod_sector, cod_programa, cod_sub_prog, cod_nivel_i, escala, grupo ASC");
            $this->set('datos_nivel_pc', $datos_nivel_pc);


            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $_SESSION['ejercicio'] = $ano_formular;
  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin funcion forma_2019




function forma_2020 ($year=null) {

set_time_limit(0);
//echo $year."   si";

	$sql='';

  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
	if($cod_dep!=1){
		$condicion.=" and cod_dep=$cod_dep";
	}

    $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$i  = 0;
	$xi = 0;
	$j  = 0;
	$k  = 0;
	$l  = 0;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
            $sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_tg_spsp_dep";
  	}else if($consolidado==1){
            $sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_tg_spsp_inst";
  	}            $datos_nivel_tc = $this->$modelo->findAll($sql."and cod_nivel_i=1",null,'escala ASC');
            $this->set('datos_nivel_tc', $datos_nivel_tc);
            $datos_nivel_tp = $this->$modelo->findAll($sql."and cod_nivel_i=2",null,'escala ASC');
            $this->set('datos_nivel_tp', $datos_nivel_tp);
            $datos_nivel_pc = $this->$modelo->findAll($sql."and cod_nivel_i=3",null,'escala ASC');
            $this->set('datos_nivel_pc', $datos_nivel_pc);
  	if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
         $cod_programa = isset($this->data['reporte']['cod_programa']) && !empty($this->data['reporte']['cod_programa']) ? " cod_programa=".$this->data['reporte']['cod_programa']:"2=2";
         $cod_sub_prog = isset($this->data['reporte']['cod_subprograma']) && !empty($this->data['reporte']['cod_subprograma']) ? " cod_sub_prog=".$this->data['reporte']['cod_subprograma']:"3=3";
  	     $sql .= " and ".$cod_sector ." and ".$cod_programa." and ".$cod_sub_prog;
  	}

  	        $DATOS_sectores= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog FROM $modelo WHERE $sql.AND (cod_nivel_i>3 AND cod_nivel_i<6) group by cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog order by cod_sector,cod_programa,cod_sub_prog ASC");
            $DATOS_res = $this->$modelo->findAll($sql." AND (cod_nivel_i>3 AND cod_nivel_i<6)");
            $this->set('data_sectores', $DATOS_sectores);
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $_SESSION['ejercicio'] = $ano_formular;
            $this->set('ocupacion',$this->v_cnmd04_ocupacion->findAll("cod_nivel_i>3 AND cod_nivel_i<6",null,"cod_nivel_i,cod_nivel_ii ASC"));

  }

$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin funcion forma_2020




function forma_2021($opcion=null, $opcion2=null, $opcion3=null){
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

                            $this->set('partida',$this->cfpd01_ano_partida->findAll('ejercicio='.$ejercicio,null, 'cod_grupo, cod_partida ASC',null, null, null));
						    $DATOS_res = $this->cfpd05->findAll($condicion, null, 'cod_sector, cod_programa, cod_partida ASC', null, null);
						    $this->set('datos', $DATOS_res);
							$_SESSION['ejercicio'] = $ano;
				            $this->set('sector',  $this->cfpd02_sector->findAll($condicion2,null,'cod_sector ASC',null,null,null));
				            $this->set('programa',$this->cfpd02_programa->findAll($condicion3,null,'cod_sector, cod_programa ASC',null,null,null));

	}//fin else

$this->set('opcion', $opcion);
$this->set('opcion2',$opcion2);
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin function forma_2021




function forma_2022($tipo=null, $year=null){
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
    $this->set('datos',$datos);
    $this->set('titulo_a', $titulo_a);
    $this->set('subprograma', $subprograma);
    $this->set('tipo', $tipo);
  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function forma 2022




function forma_2023($var1=null, $var2=null){
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

		$filtro = $sql." and a.cod_proyecto<>0 and a.ano=".$ano;
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

}//fin forma_2023




function forma_2025($var1=null, $var2=null){
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


}//fin function forma_2025




function forma_2026($var1=null, $var2=null){
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


}//function forma_2026




function forma_2027($year=null){

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

}//fin function forma_2027





function forma_2028($year=null){

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

}//fin function forma_2028




function forma_2029 ($year=null) {
set_time_limit(0);
	$sql='';
  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}

	        $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }
    $this->set('iniciorep',date('his'));
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$year = $ano;
	$condicion .= " and ano=".$year;
    if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
         $cod_programa = isset($this->data['reporte']['cod_programa']) && !empty($this->data['reporte']['cod_programa']) ? " cod_programa=".$this->data['reporte']['cod_programa']:"2=2";
         $cod_sub_prog = isset($this->data['reporte']['cod_subprograma']) && !empty($this->data['reporte']['cod_subprograma']) ? " cod_sub_prog=".$this->data['reporte']['cod_subprograma']:"3=3";
         $cod_proyecto = isset($this->data['reporte']['cod_proyecto']) && !empty($this->data['reporte']['cod_proyecto']) ? " cod_proyecto=".$this->data['reporte']['cod_proyecto']:"4=4";
  	     $sql = "".$cod_sector ." and ".$cod_programa." and ".$cod_sub_prog." and $cod_proyecto";
  	}else{
  		$sql = "1=1 ";
  	}
    if($consolidado==2){
            $sql .= " and ".$this->SQLCA_consolidado();
            $sql .= " and ano=$year";
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2126_depv2";
  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql .= " and ".$this->SQLCA_consolidado($consolidado);
            $sql .= " and ano=$year";
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2126_instv2";
  	}
  	        $DATOS_sectores= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,cod_proyecto,deno_sector,deno_programa,deno_sub_prog,deno_proyecto,deno_activ51,deno_activ52,deno_activ53,deno_activ54,deno_activ55,deno_activ56,deno_activ57,deno_activ58,deno_activ59,deno_activ60,deno_activ61,deno_activ62,deno_activ63,deno_activ64,deno_activ65,deno_activ66,deno_activ67,deno_activ68,deno_activ69,deno_activ70 FROM $modelo WHERE $sql group by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,deno_sector,deno_programa,deno_sub_prog,deno_proyecto,deno_activ51,deno_activ52,deno_activ53,deno_activ54,deno_activ55,deno_activ56,deno_activ57,deno_activ58,deno_activ59,deno_activ60,deno_activ61,deno_activ62,deno_activ63,deno_activ64,deno_activ65,deno_activ66,deno_activ67,deno_activ68,deno_activ69,deno_activ70 order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto ASC");
            $this->set('data_sectores', $DATOS_sectores);
            $DATOS_res = $this->$modelo->findAll($sql,null,'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
			$this->set('data', $DATOS_res);
            //$this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $_SESSION['ejercicio'] = $ano_formular;

  }

$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin funcion forma_2029





function forma_2030($var1=null, $var2=null){
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
				$_SESSION['ejercicio'] = $dato;
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

}//fin forma_2030





function forma_2031($var1=null, $var2=null){
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
				$_SESSION['ejercicio'] = $dato;
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

}//fin forma_2031





function forma_2032($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;

            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
			$dato = null;
			foreach($year as $year){
				$dato = $year['cfpd01_formulacion']['ano_formular'];
				$_SESSION['ejercicio'] = $dato;
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


		$filtro = $condicion." and ano_formular=".$ano;
		$consulta="SELECT * FROM v_2014_entes_descen a WHERE ".$filtro;

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

}//fin forma_2032




function forma_2033($year=null) {
set_time_limit(0);
	$sql='';

  if(!$year){
    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$_SESSION['ejercicio'] = $ano;
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$i  = 0;
	$xi = 0;
	$j  = 0;
	$k  = 0;
	$l  = 0;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
          //$sql = $this->SQLCA_consolidado();
            $sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_tg_entes";
  	}else if($consolidado==1){
          //$sql = $this->SQLCA_consolidado($consolidado);
            $sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_2014_rch_tg_entes";
  	}
            $DATOS_res = $this->$modelo->findAll($sql);
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $this->set('ocupacion',$this->v_cnmd04_ocupacion->findAll("cod_nivel_i<=3",null,"cod_nivel_i,cod_nivel_ii ASC"));

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin funcion forma_2033




function forma_2034($var1=null, $var2=null){
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

}//fin forma_2034




function forma_2035($var1=null, $var2=null){
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

}//fin function forma_2035




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



} // END CLASS

?>
