<?php

 class Cfpp05AsignacionIngresoGastoController extends AppController{

    var $name    = "cfpp05_asignacion_ingreso_gasto";
 	var $uses    = array('cfpd03','cfpd05', 'ccfd03_instalacion', 'cfpd01_formulacion', 'ccfd04_cierre_mes');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf');


function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession



function beforeFilter(){
					$this->checkSession();

}






function index($ano_seleccion=null){
    $this->layout = "ajax";
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$condicion_formulacion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' ';
	$year2 = $this->cfpd01_formulacion->findAll($condicion_formulacion, null, 'ano_formular ASC', null);
    $ano_formulacion  = $year2[0]['cfpd01_formulacion']['ano_formular'];
    $ano_ejecucion    =  $this->ano_ejecucion();
	$cod[]  = $ano_formulacion;
	$deno[] = $ano_formulacion;

	$cod[]  = $ano_ejecucion;
	$deno[] = $ano_ejecucion;
	$lista=array_combine($cod, $deno);
    $this->set("lista_year",      $lista);
	/*if($ano_seleccion==null){
	       $this->set("ano_seleccion",   $ano_ejecucion);
	       $ano_seleccion = $ano_ejecucion;
	}else{
	       $this->set("ano_seleccion",   $ano_seleccion);
	}//fin else*/
	$aleatorio_= rand();
    if(isset($ano_seleccion) && $ano_seleccion!=null){
    $this->set("ano_seleccion",   $ano_seleccion);
    $this->set("ano_select",   $ano_seleccion);
    $this->set("ano_formulacion", $ano_formulacion);
	$this->set("ano_ejecucion" ,  $ano_ejecucion);
	$datos = $this->cfpd01_formulacion->execute("
								SELECT
											  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida
								FROM cfpd05 a
								WHERE
								              a.cod_presi       =   '".$cod_presi."'      and
											  a.cod_entidad     =   '".$cod_entidad."'    and
											  a.cod_tipo_inst   =   '".$cod_tipo_inst."'  and
											  a.cod_inst        =   '".$cod_inst."'       and
											  a.ano             =   '".$ano_seleccion."'  and
											  a.cod_ramo     IS NULL                      and
											  a.cod_subramo  IS NULL                      and
											  a.cod_esp      IS NULL                      and
											  a.cod_subesp   IS NULL                      and
											  a.cod_aux      IS NULL

								GROUP BY      a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida

								ORDER BY   	  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida	;    ");

$datos2 = $this->cfpd01_formulacion->execute("
								SELECT
											  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano,
											  (SUBSTR(a.cod_partida::text, 0, 2))::int  as cod_grupo,
											  (SUBSTR(a.cod_partida::text, 2))::int     as cod_partida_sin_grupo,
											  a.cod_partida,
											  a.cod_generica,
											  a.cod_especifica,
											  a.cod_sub_espec,
											  a.cod_auxiliar,
											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_6_auxiliar b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_partida::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_partida::text, 2))::int     and
													  b.cod_generica     =   a.cod_generica    and
													  b.cod_especifica   =   a.cod_especifica  and
													  b.cod_sub_espec    =   a.cod_sub_espec   and
													  b.cod_auxiliar     =   a.cod_auxiliar
											  ) as denominacion_cod_auxiliar,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_5_sub_espec b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_partida::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_partida::text, 2))::int     and
													  b.cod_generica     =   a.cod_generica    and
													  b.cod_especifica   =   a.cod_especifica  and
													  b.cod_sub_espec    =   a.cod_sub_espec
											  ) as denominacion_cod_sub_espec,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_4_especifica b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_partida::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_partida::text, 2))::int     and
													  b.cod_generica     =   a.cod_generica    and
													  b.cod_especifica   =   a.cod_especifica
											  ) as denominacion_cod_especifica,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_3_generica b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_partida::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_partida::text, 2))::int     and
													  b.cod_generica     =   a.cod_generica
											  ) as denominacion_cod_generica,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_2_partida b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_partida::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_partida::text, 2))::int
											  ) as denominacion_cod_partida,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_1_grupo b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_partida::text, 0, 2))::int
											  ) as denominacion_cod_grupo

								FROM cfpd03 a

								WHERE
								              a.cod_presi       =   '".$cod_presi."'      and
											  a.cod_entidad     =   '".$cod_entidad."'    and
											  a.cod_tipo_inst   =   '".$cod_tipo_inst."'  and
											  a.cod_inst        =   '".$cod_inst."'       and
											  a.cod_dep         =   1                     and
											  a.ano             =   '".$ano_seleccion."'

								GROUP BY      a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano,
											  a.cod_partida,
											  a.cod_generica,
											  a.cod_especifica,
											  a.cod_sub_espec,
											  a.cod_auxiliar
								ORDER BY   	  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano,
											  a.cod_partida,
											  a.cod_generica,
											  a.cod_especifica,
											  a.cod_sub_espec,
											  a.cod_auxiliar	;    ");

$datos3 = $this->cfpd01_formulacion->execute("
								SELECT
											  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida,
											  a.cod_ramo,
											  a.cod_subramo,
											  a.cod_esp,
											  a.cod_subesp,
											  a.cod_aux,
											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_6_auxiliar b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_ramo::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_ramo::text, 2))::int     and
													  b.cod_generica     =   a.cod_subramo    and
													  b.cod_especifica   =   a.cod_esp  and
													  b.cod_sub_espec    =   a.cod_subesp   and
													  b.cod_auxiliar     =   a.cod_aux
											  ) as denominacion_cod_auxiliar,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_5_sub_espec b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_ramo::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_ramo::text, 2))::int     and
													  b.cod_generica     =   a.cod_subramo    and
													  b.cod_especifica   =   a.cod_esp  and
													  b.cod_sub_espec    =   a.cod_subesp
											  ) as denominacion_cod_sub_espec,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_4_especifica b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_ramo::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_ramo::text, 2))::int     and
													  b.cod_generica     =   a.cod_subramo    and
													  b.cod_especifica   =   a.cod_esp
											  ) as denominacion_cod_especifica,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_3_generica b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_ramo::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_ramo::text, 2))::int     and
													  b.cod_generica     =   a.cod_subramo
											  ) as denominacion_cod_generica,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_2_partida b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_ramo::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_ramo::text, 2))::int
											  ) as denominacion_cod_partida,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_1_grupo b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_ramo::text, 0, 2))::int
											  ) as denominacion_cod_grupo

								FROM cfpd05 a

								WHERE
								              a.cod_presi       =   '".$cod_presi."'      and
											  a.cod_entidad     =   '".$cod_entidad."'    and
											  a.cod_tipo_inst   =   '".$cod_tipo_inst."'  and
											  a.cod_inst        =   '".$cod_inst."'       and
											  a.ano             =   '".$ano_seleccion."'  and
											  a.cod_ramo     IS NOT NULL                      and
											  a.cod_subramo  IS NOT NULL                      and
											  a.cod_esp      IS NOT NULL                      and
											  a.cod_subesp   IS NOT NULL                      and
											  a.cod_aux      IS NOT NULL

								GROUP BY      a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida,
											  a.cod_ramo,
											  a.cod_subramo,
											  a.cod_esp,
											  a.cod_subesp,
											  a.cod_aux


								ORDER BY   	  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida	;    ");

					$this->set("datos" ,  $datos);
					$this->set("datos2",   $datos2);
					$this->set("datos3",   $datos3);
    }//fin if ano_seleccion

}//fin function index












function procesar($var0  = null,
                  $var1  = null,
                  $var2  = null,
                  $var3  = null,
                  $var4  = null,
                  $var5  = null,
                  $var6  = null,
                  $var7  = null,
                  $var8  = null,
                  $var9  = null,
                  $var10 = null,
                  $var11 = null){

$this->layout = "ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');



  $ano            = $var0;
  $cod_sector     = $var1;
  $cod_programa   = $var2;
  $cod_sub_prog   = $var3;
  $cod_proyecto   = $var4;
  $cod_activ_obra = $var5;
  $cod_partida    = $var6;
  $cod_ramo       = $var7;
  $cod_subramo    = $var8;
  $cod_esp        = $var9;
  $cod_subesp     = $var10;
  $cod_aux        = $var11;






$datos3 = $this->cfpd01_formulacion->execute("  UPDATE  cfpd05 SET cod_ramo    = '".$cod_ramo."',
		                                                           cod_subramo = '".$cod_subramo."',
				                                                   cod_esp     = '".$cod_esp."',
				                                                   cod_subesp  = '".$cod_subesp."',
				                                                   cod_aux     = '".$cod_aux."'
												WHERE
												              cod_presi       =   '".$cod_presi."'      and
															  cod_entidad     =   '".$cod_entidad."'    and
															  cod_tipo_inst   =   '".$cod_tipo_inst."'  and
															  cod_inst        =   '".$cod_inst."'       and
															  ano             =   '".$ano."'            and
															  cod_sector      =   '".$cod_sector."'     and
															  cod_programa    =   '".$cod_programa."'   and
															  cod_sub_prog    =   '".$cod_sub_prog."'   and
															  cod_proyecto    =   '".$cod_proyecto."'   and
															  cod_activ_obra  =   '".$cod_activ_obra."' and
															  cod_partida     =   '".$cod_partida."'       ");

      if($datos3>1){
        $this->set("Message_existe", "REALIZADA ASOCIACIÓN DE INGRESOS Y GASTOS DE LA CATEGORIA PROGRAMATICA SELECCIONADA");
      }else{
      	$this->set("errorMessage",   "NO FUE REALIZADA ASOCIACIÓN DE INGRESOS Y GASTOS DE LA CATEGORIA PROGRAMATICA");
      }//fin else


	$this->procesar_render($ano);
	$this->render('procesar_render');


}//fin funcion procesar



function procesar_render($ano_seleccion=null){
    $this->layout = "ajax";
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$condicion_formulacion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' ';
	$year2 = $this->cfpd01_formulacion->findAll($condicion_formulacion, null, 'ano_formular ASC', null);
    $ano_formulacion  = $year2[0]['cfpd01_formulacion']['ano_formular'];
    $ano_ejecucion    =  $this->ano_ejecucion();
    //echo "hola ". rand();
	//$aleatorio_= rand();
    if(isset($ano_seleccion) && $ano_seleccion!=null){
    $this->set("ano_seleccion",   $ano_seleccion);
    $this->set("ano_formulacion", $ano_formulacion);
	$this->set("ano_ejecucion" ,  $ano_ejecucion);
	$datos = $this->cfpd01_formulacion->execute("
								SELECT
											  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida
								FROM cfpd05 a
								WHERE
								              a.cod_presi       =   '".$cod_presi."'      and
											  a.cod_entidad     =   '".$cod_entidad."'    and
											  a.cod_tipo_inst   =   '".$cod_tipo_inst."'  and
											  a.cod_inst        =   '".$cod_inst."'       and
											  a.ano             =   '".$ano_seleccion."'  and
											  a.cod_ramo     IS NULL                      and
											  a.cod_subramo  IS NULL                      and
											  a.cod_esp      IS NULL                      and
											  a.cod_subesp   IS NULL                      and
											  a.cod_aux      IS NULL

								GROUP BY      a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida

								ORDER BY   	  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida	;    ");

$datos2 = $this->cfpd01_formulacion->execute("
								SELECT
											  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano,
											  (SUBSTR(a.cod_partida::text, 0, 2))::int  as cod_grupo,
											  (SUBSTR(a.cod_partida::text, 2))::int     as cod_partida_sin_grupo,
											  a.cod_partida,
											  a.cod_generica,
											  a.cod_especifica,
											  a.cod_sub_espec,
											  a.cod_auxiliar,
											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_6_auxiliar b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_partida::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_partida::text, 2))::int     and
													  b.cod_generica     =   a.cod_generica    and
													  b.cod_especifica   =   a.cod_especifica  and
													  b.cod_sub_espec    =   a.cod_sub_espec   and
													  b.cod_auxiliar     =   a.cod_auxiliar
											  ) as denominacion_cod_auxiliar,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_5_sub_espec b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_partida::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_partida::text, 2))::int     and
													  b.cod_generica     =   a.cod_generica    and
													  b.cod_especifica   =   a.cod_especifica  and
													  b.cod_sub_espec    =   a.cod_sub_espec
											  ) as denominacion_cod_sub_espec,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_4_especifica b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_partida::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_partida::text, 2))::int     and
													  b.cod_generica     =   a.cod_generica    and
													  b.cod_especifica   =   a.cod_especifica
											  ) as denominacion_cod_especifica,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_3_generica b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_partida::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_partida::text, 2))::int     and
													  b.cod_generica     =   a.cod_generica
											  ) as denominacion_cod_generica,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_2_partida b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_partida::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_partida::text, 2))::int
											  ) as denominacion_cod_partida,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_1_grupo b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_partida::text, 0, 2))::int
											  ) as denominacion_cod_grupo

								FROM cfpd03 a

								WHERE
								              a.cod_presi       =   '".$cod_presi."'      and
											  a.cod_entidad     =   '".$cod_entidad."'    and
											  a.cod_tipo_inst   =   '".$cod_tipo_inst."'  and
											  a.cod_inst        =   '".$cod_inst."'       and
											  a.cod_dep         =   1                     and
											  a.ano             =   '".$ano_seleccion."'

								GROUP BY      a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano,
											  a.cod_partida,
											  a.cod_generica,
											  a.cod_especifica,
											  a.cod_sub_espec,
											  a.cod_auxiliar
								ORDER BY   	  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano,
											  a.cod_partida,
											  a.cod_generica,
											  a.cod_especifica,
											  a.cod_sub_espec,
											  a.cod_auxiliar	;    ");

$datos3 = $this->cfpd01_formulacion->execute("
								SELECT
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
											  a.cod_activ_obra,
											  a.cod_partida,
											  a.cod_ramo,
											  a.cod_subramo,
											  a.cod_esp,
											  a.cod_subesp,
											  a.cod_aux,
											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_6_auxiliar b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_ramo::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_ramo::text, 2))::int     and
													  b.cod_generica     =   a.cod_subramo    and
													  b.cod_especifica   =   a.cod_esp  and
													  b.cod_sub_espec    =   a.cod_subesp   and
													  b.cod_auxiliar     =   a.cod_aux
											  ) as denominacion_cod_auxiliar,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_5_sub_espec b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_ramo::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_ramo::text, 2))::int     and
													  b.cod_generica     =   a.cod_subramo    and
													  b.cod_especifica   =   a.cod_esp  and
													  b.cod_sub_espec    =   a.cod_subesp
											  ) as denominacion_cod_sub_espec,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_4_especifica b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_ramo::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_ramo::text, 2))::int     and
													  b.cod_generica     =   a.cod_subramo    and
													  b.cod_especifica   =   a.cod_esp
											  ) as denominacion_cod_especifica,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_3_generica b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_ramo::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_ramo::text, 2))::int     and
													  b.cod_generica     =   a.cod_subramo
											  ) as denominacion_cod_generica,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_2_partida b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_ramo::text, 0, 2))::int  and
													  b.cod_partida      =   (SUBSTR(a.cod_ramo::text, 2))::int
											  ) as denominacion_cod_partida,

											  (SELECT
											          b.denominacion
											  FROM cfpd01_ano_1_grupo b

											  WHERE   b.ejercicio        =   a.ano  and
											          b.cod_grupo        =   (SUBSTR(a.cod_ramo::text, 0, 2))::int
											  ) as denominacion_cod_grupo

								FROM cfpd05 a

								WHERE
								              a.cod_presi       =   '".$cod_presi."'      and
											  a.cod_entidad     =   '".$cod_entidad."'    and
											  a.cod_tipo_inst   =   '".$cod_tipo_inst."'  and
											  a.cod_inst        =   '".$cod_inst."'       and
											  a.cod_dep         =   '".$cod_dep."'        and
											  a.ano             =   '".$ano_seleccion."'  and
											  a.cod_ramo     IS NOT NULL                      and
											  a.cod_subramo  IS NOT NULL                      and
											  a.cod_esp      IS NOT NULL                      and
											  a.cod_subesp   IS NOT NULL                      and
											  a.cod_aux      IS NOT NULL

								GROUP BY      a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida,
											  a.cod_ramo,
											  a.cod_subramo,
											  a.cod_esp,
											  a.cod_subesp,
											  a.cod_aux


								ORDER BY   	  a.cod_presi,
											  a.cod_entidad,
											  a.cod_tipo_inst,
											  a.cod_inst,
											  a.cod_dep,
											  a.ano,
											  a.cod_sector,
											  a.cod_programa,
											  a.cod_sub_prog,
											  a.cod_proyecto,
											  a.cod_activ_obra,
											  a.cod_partida	;    ");

					$this->set("datos" ,  $datos);
					$this->set("datos2",   $datos2);
					$this->set("datos3",   $datos3);
    }//fin if ano_seleccion

}//fin function procesar render







function eliminar($var0  = null,
                  $var1  = null,
                  $var2  = null,
                  $var3  = null,
                  $var4  = null,
                  $var5  = null,
                  $var6  = null,
                  $var7  = null,
                  $var8  = null,
                  $var9  = null,
                  $var10 = null,
                  $var11 = null){

$this->layout = "ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');



  $ano            = $var0;
  $cod_sector     = $var1;
  $cod_programa   = $var2;
  $cod_sub_prog   = $var3;
  $cod_proyecto   = $var4;
  $cod_activ_obra = $var5;
  $cod_partida    = $var6;



$datos3 = $this->cfpd01_formulacion->execute("  UPDATE  cfpd05 SET cod_ramo    = null,
		                                                           cod_subramo = null,
				                                                   cod_esp     = null,
				                                                   cod_subesp  = null,
				                                                   cod_aux     = null
												WHERE
												              cod_presi       =   '".$cod_presi."'      and
															  cod_entidad     =   '".$cod_entidad."'    and
															  cod_tipo_inst   =   '".$cod_tipo_inst."'  and
															  cod_inst        =   '".$cod_inst."'       and
															  ano             =   '".$ano."'            and
															  cod_sector      =   '".$cod_sector."'     and
															  cod_programa    =   '".$cod_programa."'   and
															  cod_sub_prog    =   '".$cod_sub_prog."'   and
															  cod_proyecto    =   '".$cod_proyecto."'   and
															  cod_activ_obra  =   '".$cod_activ_obra."' and
															  cod_partida     =   '".$cod_partida."'       ");

      if($datos3>1){
        $this->set("Message_existe", "ELIMINADA ASOCIACIÓN DE INGRESOS Y GASTOS DE LA CATEGORIA PROGRAMATICA SELECCIONADA");
      }else{
      	$this->set("errorMessage",   "NO FUE ELIMINADA ASOCIACIÓN DE INGRESOS Y GASTOS DE LA CATEGORIA PROGRAMATICA");
      }//fin else


	$this->index($var0);
	$this->render("index");
    //$this->procesar_render($ano);
	//$this->render('procesar_render');

}//fin funcion






}//fin class

?>