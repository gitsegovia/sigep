<?php


class Graficos1Controller extends AppController{

    var $uses = array('cfpd07_clasificacion_recurso', 'cfpd07_plan_inversion', 'cfpd07_obras_cuerpo', 'ccfd04_cierre_mes');

	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');


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



function beforeFilter(){$this->checkSession();}









function contratado($opcion=null){



	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);

      $SScoddeporig             =       $this->Session->read('SScoddeporig');
	  $SScoddep                 =       $this->Session->read('SScoddep');
	  $Modulo                   =       $this->Session->read('Modulo');
	  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
	  $lista = "";





	if(isset($this->data['cfpp05']['consolidacion'])){if($this->data['cfpp05']['consolidacion']==2){$Modulo="2";}}



	if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
	 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";
	    $this->set('global', 'si');
	}else{
		$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
	    $this->set('global', 'no');
	}//fin else



        $this->set('opcion', $opcion);
        $this->set('year', $ano);

	     if($opcion=='si'){
		   $this->layout="ajax";


    				$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras

				 		      FROM cfpd07_obras_cuerpo a where ".$cond." and  a.ano_estimacion  =   ".$ano."

                        GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst,  a.ano_estimacion
    					ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst DESC;");


                 $total_presupuestado = ($datos[0][0]['asignacion_total'] + $datos[0][0]['aumento_obras']) -  $datos[0][0]['disminucion_obras'];
                 $monto_contratado    = ($datos[0][0]['monto_presupuestado'] + $datos[0][0]['aumento_obras']) -  $datos[0][0]['disminucion_obras'];
                 $diferencia          =  $total_presupuestado - $monto_contratado;

                 $this->set('total_presupuestado',  $total_presupuestado);
                 $this->set('monto_contratado',     $monto_contratado);
                 $this->set('diferencia',           $diferencia);


	}else{
		   $this->layout = "pdf";
		   $username = $this->Session->read('nom_usuario');
			$this->set('user', $username);
			$total_presupuestado       =   $this->data["graficos1"]["total_presupuestado"];
			$monto_contratado          =   $this->data["graficos1"]["monto_contratado"];
			$diferencia                =   $this->data["graficos1"]["diferencia"];
			$por_monto_contratado      =   $this->data["graficos1"]["por_monto_contratado"];
			$por_total_presupuestado   =   $this->data["graficos1"]["por_total_presupuestado"];
			$por_diferencia            =   $this->data["graficos1"]["por_diferencia"];
			$rdm                       =   $this->data["graficos1"]["rdm"];
			$year                      =   $this->data["graficos1"]["year"];
			$tipo_recurso              =   $this->data["graficos1"]["tipo_recurso"];
			$clasificacion_recurso     =   $this->data["graficos1"]["clasificacion_recurso"];



            $this->set('year', $year);
                 if($clasificacion_recurso=='TODO' || $clasificacion_recurso==''){
                 	           $datos[0][0]['denominacion']="";
                 }else{
                   	  $datos = $this->cfpd07_plan_inversion->execute("SELECT
								  a.cod_presi,
								  a.cod_entidad,
								  a.cod_tipo_inst,
								  a.cod_inst,
								  a.ano_recurso,
								  a.tipo_recurso,
								  a.clasificacion_recurso,
								  b.denominacion,
								  a.asignacion_total,
								  a.monto_presupuestado


								FROM cfpd07_plan_inversion a ,  cfpd07_clasificacion_recurso b  where


								    a.cod_presi                =   ".$cod_presi." and
								    a.cod_entidad              =   ".$cod_entidad." and
								    a.cod_tipo_inst            =   ".$cod_tipo_inst." and
								    a.cod_inst                 =   ".$cod_inst." and
								    b.cod_presi                =     a.cod_presi and
								    b.cod_entidad              =     a.cod_entidad and
								    b.cod_tipo_inst            =     a.cod_tipo_inst and
								    b.cod_inst                 =     a.cod_inst and
								    a.tipo_recurso             =   ".$tipo_recurso." and
								    a.ano_recurso              =   ".$year." and
								    a.clasificacion_recurso    =   ".$clasificacion_recurso." and
								    a.tipo_recurso             =   b.tipo_recurso and
								    a.clasificacion_recurso    =   b.clasificacion_recurso


								ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.tipo_recurso DESC;");

                            $datos[0][0]['denominacion'] = ' / '.$datos[0][0]['denominacion'];
                         }//fin else

            $tipo_recurso++;
            $opcion1_aux = "";
                         if($tipo_recurso==1){  $opcion1_aux = "";
				   }else if($tipo_recurso==2){  $opcion1_aux = "(Ordinario)";
				   }else if($tipo_recurso==3){  $opcion1_aux = "(Coordinado)";
				   }else if($tipo_recurso==4){  $opcion1_aux = "(Laee)";
				   }else if($tipo_recurso==5){  $opcion1_aux = "(Fides)";
				   }else if($tipo_recurso==6){  $opcion1_aux = "(Ingreso Extraordinario)";
				   }else if($tipo_recurso==9){  $opcion1_aux = ""; }//fin else

            $this->set('opcion1_aux', $opcion1_aux);
            $this->set('opcion2_aux', $datos[0][0]['denominacion']);
            $this->set('tipo_recurso', $tipo_recurso);
            $this->set('clasificacion_recurso', $clasificacion_recurso);
			$this->set('total_presupuestado', $total_presupuestado);
			$this->set('monto_contratado', $monto_contratado);
			$this->set('diferencia', $diferencia);
			$this->set('por_monto_contratado', $por_monto_contratado);
			$this->set('por_total_presupuestado', $por_total_presupuestado);
			$this->set('por_diferencia', $por_diferencia);
			$this->set('rdm', $rdm);


         }//fin function

$this->set('titulo_inst', $this->Session->read('entidad_federal'));
$this->set('titulo_a',$this->Session->read('dependencia'));


}//fin function











function clasificacion_recurso($var2=null, $var1=null){


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $this->layout="ajax";
    $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));
    $this->set('username', $username);
    $this->set('rdm', $rdm);


  if($var1!=null && $var1!=6){
       $lista = "";

       	  $datos = $this->cfpd07_plan_inversion->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.ano_recurso,
  a.tipo_recurso,
  a.clasificacion_recurso,
  b.denominacion,
  a.asignacion_total,
  a.monto_presupuestado


FROM cfpd07_plan_inversion a ,  cfpd07_clasificacion_recurso b  where


    a.cod_presi                =   ".$cod_presi." and
    a.cod_entidad              =   ".$cod_entidad." and
    a.cod_tipo_inst            =   ".$cod_tipo_inst." and
    a.cod_inst                 =   ".$cod_inst." and
    b.cod_presi                =     a.cod_presi and
    b.cod_entidad              =     a.cod_entidad and
    b.cod_tipo_inst            =     a.cod_tipo_inst and
    b.cod_inst                 =     a.cod_inst and
    a.tipo_recurso             =   ".$var1." and
    a.ano_recurso              =   ".$var2." and
    a.tipo_recurso             =   b.tipo_recurso and
    a.clasificacion_recurso    =   b.clasificacion_recurso


ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.tipo_recurso DESC;");

  foreach($datos as $ve){$lista[$ve[0]['clasificacion_recurso']] = $ve[0]['denominacion']; }// fin

			    $this->set('clasificacion', $lista);
			    $this->set('cod_dep', $cod_dep);



  }else{
       if($var1==6){$this->set('vacio', 'si');}
  }//fin else

 $this->set('year', $var2);
 $this->set('tipo_recurso', $var1);

}//fin function








function clasificacion_recurso_pagado($var2=null, $var1=null){


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $this->layout="ajax";
    $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));
    $this->set('username', $username);
    $this->set('rdm', $rdm);


  if($var1!=null && $var1!=6){
       $lista = "";

       	  $datos = $this->cfpd07_plan_inversion->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.ano_recurso,
  a.tipo_recurso,
  a.clasificacion_recurso,
  b.denominacion,
  a.asignacion_total,
  a.monto_presupuestado


FROM cfpd07_plan_inversion a ,  cfpd07_clasificacion_recurso b  where


    a.cod_presi                =   ".$cod_presi." and
    a.cod_entidad              =   ".$cod_entidad." and
    a.cod_tipo_inst            =   ".$cod_tipo_inst." and
    a.cod_inst                 =   ".$cod_inst." and
    b.cod_presi                =     a.cod_presi and
    b.cod_entidad              =     a.cod_entidad and
    b.cod_tipo_inst            =     a.cod_tipo_inst and
    b.cod_inst                 =     a.cod_inst and
    a.tipo_recurso             =   ".$var1." and
    a.ano_recurso              =   ".$var2." and
    a.tipo_recurso             =   b.tipo_recurso and
    a.clasificacion_recurso    =   b.clasificacion_recurso


ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.tipo_recurso DESC;");

  foreach($datos as $ve){$lista[$ve[0]['clasificacion_recurso']] = $ve[0]['denominacion']; }// fin

			    $this->set('clasificacion', $lista);
			    $this->set('cod_dep', $cod_dep);



  }else{
       if($var1==6){$this->set('vacio', 'si');}
  }//fin else

 $this->set('year', $var2);
 $this->set('tipo_recurso', $var1);

}//fin function











function cuerpo($var2=null, $var1=null, $var3=null){


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $this->layout="ajax";
    $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);  $cond = "";
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));

      $SScoddeporig             =       $this->Session->read('SScoddeporig');
	  $SScoddep                 =       $this->Session->read('SScoddep');
	  $Modulo                   =       $this->Session->read('Modulo');
	  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
	  $lista = "";





	if(isset($_SESSION['consolidado_select_ventas_dependencia'])){if($_SESSION['consolidado_select_ventas_dependencia']==2){$Modulo="2";}}


	if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
	 	$cond = " ";
	    $this->set('global', 'si');
	}else{
		$cond = "  and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
	    $this->set('global', 'no');
	}//fin else

    if($var3!='todo' && $var3!=null){
                $cond .= " and a.clasificacion_recurso             =   ".$var3." ";
    }//fin else

     if($var1!=6){


    					$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras

				 		      FROM cfpd07_obras_cuerpo a where
				 		      		a.cod_presi             =   ".$cod_presi." and
								    a.cod_entidad           =   ".$cod_entidad." and
								    a.cod_tipo_inst         =   ".$cod_tipo_inst." and
								    a.cod_inst              =   ".$cod_inst." and
								    a.tipo_recurso          =   ".$var1.$cond." and
							 		a.ano_estimacion        =   ".$var2."

                        GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst,  a.ano_estimacion
    					ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst DESC;");




     }else{


    					$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras

				 		      FROM cfpd07_obras_cuerpo a where
				 		      		a.cod_presi             =   ".$cod_presi." and
								    a.cod_entidad           =   ".$cod_entidad." and
								    a.cod_tipo_inst         =   ".$cod_tipo_inst." and
								    a.cod_inst              =   ".$cod_inst.$cond." and
							 		a.ano_estimacion        =   ".$var2."

                        GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst,  a.ano_estimacion
    					ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst DESC;");




     }//fin else

              if(!isset($datos[0][0]['asignacion_total'])){
                   $datos[0][0]['asignacion_total'] = "";
                   $datos[0][0]['monto_presupuestado'] = "";
                   $datos[0][0]['aumento_obras'] = "";
                   $datos[0][0]['disminucion_obras'] = "";
              }//fin if

                 $total_presupuestado = ($datos[0][0]['asignacion_total'] + $datos[0][0]['aumento_obras']) -  $datos[0][0]['disminucion_obras'];
                 $monto_contratado    = ($datos[0][0]['monto_presupuestado'] + $datos[0][0]['aumento_obras']) -  $datos[0][0]['disminucion_obras'];
                 $diferencia          =  $total_presupuestado - $monto_contratado;

                 $this->set('total_presupuestado',  $total_presupuestado);
                 $this->set('monto_contratado',     $monto_contratado);
                 $this->set('diferencia',           $diferencia);


                 $this->set('year', $var2);
                 $this->set('tipo_recurso', $var1);
                 $this->set('clasificacion_recurso', $var3);





}//fin function











function cambio_year_cuerpo($var2=null, $var1=null, $var3=null){


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $var2;
    $var1 = 6;
    $this->layout="ajax";
    $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);  $cond = "";
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));

      $SScoddeporig             =       $this->Session->read('SScoddeporig');
	  $SScoddep                 =       $this->Session->read('SScoddep');
	  $Modulo                   =       $this->Session->read('Modulo');
	  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
	  $lista = "";





	if(isset($_SESSION['consolidado_select_ventas_dependencia'])){if($_SESSION['consolidado_select_ventas_dependencia']==2){$Modulo="2";}}


	if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
	 	$cond = " ";
	    $this->set('global', 'si');
	}else{
		$cond = "  and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
	    $this->set('global', 'no');
	}//fin else

    if($var3!='todo' && $var3!=null){
                $cond .= " and a.clasificacion_recurso             =   ".$var3." ";
    }//fin else

     if($var1!=6){


    					$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras

				 		      FROM cfpd07_obras_cuerpo a where
				 		      		a.cod_presi             =   ".$cod_presi." and
								    a.cod_entidad           =   ".$cod_entidad." and
								    a.cod_tipo_inst         =   ".$cod_tipo_inst." and
								    a.cod_inst              =   ".$cod_inst." and
								    a.tipo_recurso          =   ".$var1.$cond." and
							 		a.ano_estimacion        =   ".$var2."

                        GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst,  a.ano_estimacion
    					ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst DESC;");




     }else{


    					$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras

				 		      FROM cfpd07_obras_cuerpo a where
				 		      		a.cod_presi             =   ".$cod_presi." and
								    a.cod_entidad           =   ".$cod_entidad." and
								    a.cod_tipo_inst         =   ".$cod_tipo_inst." and
								    a.cod_inst              =   ".$cod_inst.$cond." and
							 		a.ano_estimacion        =   ".$var2."

                        GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst,  a.ano_estimacion
    					ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst DESC;");




     }//fin else

              if(!isset($datos[0][0]['asignacion_total'])){
                   $datos[0][0]['asignacion_total'] = "";
                   $datos[0][0]['monto_presupuestado'] = "";
                   $datos[0][0]['aumento_obras'] = "";
                   $datos[0][0]['disminucion_obras'] = "";
              }//fin if


                 $total_presupuestado = ($datos[0][0]['asignacion_total'] + $datos[0][0]['aumento_obras']) -  $datos[0][0]['disminucion_obras'];
                 $monto_contratado    = ($datos[0][0]['monto_presupuestado'] + $datos[0][0]['aumento_obras']) -  $datos[0][0]['disminucion_obras'];
                 $diferencia          =  $total_presupuestado - $monto_contratado;

                 $this->set('total_presupuestado',  $total_presupuestado);
                 $this->set('monto_contratado',     $monto_contratado);
                 $this->set('diferencia',           $diferencia);


                 $this->set('year', $var2);
                 $this->set('tipo_recurso', $var1);
                 $this->set('clasificacion_recurso', $var3);





}//fin function















function ano_recurso($var1=null){


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $this->layout="ajax";
    $this->set('year', $var1);
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));



}//fin function





function limpiar(){

	$this->layout="ajax";

	echo "<script>
			     if(document.getElementById('tipo_recurso')){
		                  document.getElementById('tipo_recurso_1').checked = false;
		                  document.getElementById('tipo_recurso_2').checked = false;
		                  document.getElementById('tipo_recurso_3').checked = false;
		                  document.getElementById('tipo_recurso_4').checked = false;
		                  document.getElementById('tipo_recurso_5').checked = false;
		                  document.getElementById('tipo_recurso_6').checked = false;
				 }//fin fi
         </script>";

}//fin functipon


function ano_recurso_asignacion($var1=null){


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $this->layout="ajax";
    $this->set('year', $var1);
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));

    $this->Session->write('ano_recurso_asignacion', $var1);




}//fin function









function ano_recurso_pagado($var1=null){


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $this->layout="ajax";
    $this->set('year', $var1);
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));




}//fin function















function relacion_obra_segun_asignacion($opcion=null){


    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);


        $this->set('opcion', $opcion);
        $this->set('year', $ano);

	     if($opcion=='si'){
		   $this->layout="ajax";

		   $year = $this->ano_ejecucion();
		   $this->Session->write('ano_recurso_asignacion', $year);


		    if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
			 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  ";
			    $cod_dep_sql = " ";
			    $cod_dep_sql2 = "";
			    $this->set('global', 'si');
			}else{
				$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
			    $cod_dep_sql  = "and x.cod_dep_original=a.cod_dep_original";
			    $cod_dep_sql2 = " , a.cod_dep";
			    $this->set('global', 'no');
			}//fin else

				 $datos = $this->cfpd07_plan_inversion->execute("SELECT
									a.cod_presi,
									a.cod_entidad,
									a.cod_tipo_inst,
									a.cod_inst ".$cod_dep_sql2.",
									(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." and status=1 group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql2.") as asignacion_inicial,
									(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." and status=2 group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql2.") as credito_adicional,
									(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year."              group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql2.") as total,
                                    (SELECT SUM(aumento_obras)      FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year."        group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql2.") as aumento_obras,
                                    (SELECT SUM(disminucion_obras)  FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year."        group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql2.") as disminucion_obras
									FROM
									       cfpd07_obras_cuerpo a
									WHERE
									  ".$cond." and  a.ano_estimacion    =    ".$year."
									group by
										a.cod_presi,
										a.cod_entidad,
										a.cod_tipo_inst,
										a.cod_inst ".$cod_dep_sql2."
									order by
										a.cod_presi,
										a.cod_entidad,
										a.cod_tipo_inst,
										a.cod_inst ".$cod_dep_sql2.";	");

                 $this->set('asignacion_inicial', $datos[0][0]['asignacion_inicial']);
                 $this->set('credito_adicional', $datos[0][0]['credito_adicional']);
                 $this->set('total', ($datos[0][0]['total'] + $datos[0][0]['aumento_obras']) - $datos[0][0]['disminucion_obras']);


	}else{

		    $this->layout = "pdf";


		    $username = $this->Session->read('nom_usuario');
			$this->set('user', $username);
			$rdm                       =   $this->data["graficos1"]["rdm"];
			$year                      =   $this->data["graficos1"]["year"];
			$tipo_recurso              =   $this->data["graficos1"]["tipo_recurso"];
			$clasificacion_recurso     =   $this->data["graficos1"]["clasificacion_recurso"];
			$credito_adicional         =   $this->data["graficos1"]["credito_adicional"];
			$asignacion_inicial        =   $this->data["graficos1"]["asignacion_inicial"];
			$total                     =   $this->data["graficos1"]["total"];
			$por_credito_adicional     =   $this->data["graficos1"]["por_credito_adicional"];
			$por_asignacion_inicial    =   $this->data["graficos1"]["por_asignacion_inicial"];
			$por_total                 =   $this->data["graficos1"]["por_total"];

		    $this->set('credito_adicional',      $credito_adicional);
			$this->set('asignacion_inicial',     $asignacion_inicial);
			$this->set('total',                  $total);
			$this->set('por_credito_adicional',  $por_credito_adicional);
			$this->set('por_asignacion_inicial', $por_asignacion_inicial);
			$this->set('por_total',              $por_total);


            $this->set('year', $year);

               if(isset($this->data['cfpp05']['consolidacion'])){if($this->data['cfpp05']['consolidacion']==2){$Modulo="2";}}
			          if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
						 	$cod_dep_sql = "";
						    $cod_dep_sql2 = "";
						    $this->set('global', 'si');
						}else{
							$cod_dep_sql  = " a.cod_dep_original=".$cod_dep." and  a.cod_dep=".$this->cod_dep_consolidado()." and ";
						    $cod_dep_sql2 = " , a.cod_dep";
						    $this->set('global', 'no');
						}//fin else

						if($tipo_recurso!=6){$cod_dep_sql  .= "a.tipo_recurso=".$tipo_recurso." and";}

                   	  $datos = $this->cfpd07_plan_inversion->execute("SELECT
								  a.cod_presi,
								  a.cod_entidad,
								  a.cod_tipo_inst,
								  a.cod_inst,
								  a.cod_dep,
								  a.ano_estimacion,
								  a.cod_obra,
								  a.status,
								  a.denominacion,
								  a.cod_dep_original,
								  d.denominacion as denominacion_dep,
								  (SELECT SUM(estimado_presu)     FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_dep = a.cod_dep and x.cod_obra = a.cod_obra and  x.ano_estimacion    =    ".$year." and a.status=1) as asignacion_inicial,
								  (SELECT SUM(monto_aumento)      FROM  cfpd07_obras_partidas b, cfpd10_reformulacion_partidas y   WHERE  b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and  b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_obra=a.cod_obra  and  b.ano_estimacion=a.ano_estimacion and  y.cod_presi=a.cod_presi and  y.cod_entidad=a.cod_entidad and  y.cod_tipo_inst=a.cod_tipo_inst and  y.codi_dep=a.cod_dep  and  y.ano=b.ano_estimacion and  y.cod_sector=b.cod_sector and  y.cod_programa=b.cod_programa and  y.cod_sub_prog=b.cod_sub_prog and  y.cod_proyecto=b.cod_proyecto and  y.cod_activ_obra=b.cod_activ_obra and  y.cod_partida=b.cod_partida and  y.cod_generica=b.cod_generica and  y.cod_especifica=b.cod_especifica and  y.cod_sub_espec=b.cod_sub_espec and  y.cod_auxiliar=b.cod_auxiliar  and  b.ano_estimacion    =    ".$year." and a.status=2)  as monto_aumento,
								  (SELECT SUM(monto_disminucion)  FROM  cfpd07_obras_partidas b, cfpd10_reformulacion_partidas yy  WHERE  b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and  b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_obra=a.cod_obra  and  b.ano_estimacion=a.ano_estimacion and yy.cod_presi=a.cod_presi and yy.cod_entidad=a.cod_entidad and yy.cod_tipo_inst=a.cod_tipo_inst and yy.codi_dep=a.cod_dep  and yy.ano=b.ano_estimacion and yy.cod_sector=b.cod_sector and yy.cod_programa=b.cod_programa and yy.cod_sub_prog=b.cod_sub_prog and yy.cod_proyecto=b.cod_proyecto and yy.cod_activ_obra=b.cod_activ_obra and yy.cod_partida=b.cod_partida and yy.cod_generica=b.cod_generica and yy.cod_especifica=b.cod_especifica and yy.cod_sub_espec=b.cod_sub_espec and yy.cod_auxiliar=b.cod_auxiliar  and  b.ano_estimacion    =    ".$year." and a.status=2)  as  monto_disminucion



								FROM cfpd07_obras_cuerpo  a ,    cugd02_dependencias d  where


								      a.cod_presi                =   ".$cod_presi." and
								      a.cod_entidad              =   ".$cod_entidad." and
								      a.cod_tipo_inst            =   ".$cod_tipo_inst." and
								      a.cod_inst                 =   ".$cod_inst." and ".$cod_dep_sql."
									  d.cod_tipo_institucion     =    a.cod_tipo_inst and
								      d.cod_institucion          =    a.cod_inst and
								      d.cod_dependencia          =    a.cod_dep_original and  a.ano_estimacion    =    ".$year."


								  group by
										  a.cod_presi,
										  a.cod_entidad,
										  a.cod_tipo_inst,
										  a.cod_inst,
										  a.cod_dep,
										  a.status,
										  a.ano_estimacion,
										  a.cod_obra,
										  a.denominacion,
										  a.cod_dep_original,
										  d.denominacion

								ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep_original, a.cod_obra ASC;");

                 	           $datos2[0][0]['denominacion']="";




         if($tipo_recurso==1){  $opcion1_aux = "Ordinario";
   }else if($tipo_recurso==2){  $opcion1_aux = "Coordinado";
   }else if($tipo_recurso==3){  $opcion1_aux = "Laee";
   }else if($tipo_recurso==4){  $opcion1_aux = "Fides";
   }else if($tipo_recurso==5){  $opcion1_aux = "Ingreso Extraordinario";
   }else if($tipo_recurso==6){  $opcion1_aux = ""; }//fin else

            $this->set('opcion1_aux', $opcion1_aux);
            $this->set('opcion2_aux', $datos2[0][0]['denominacion']);
            $this->set('resultado', $datos);
            $this->set('tipo_recurso', $tipo_recurso);
            $this->set('clasificacion_recurso', $clasificacion_recurso);

			$this->set('rdm', $rdm);


         }//fin function

$this->set('titulo_inst', $this->Session->read('entidad_federal'));
$this->set('titulo_a',$this->Session->read('dependencia'));




}//fin function






function cuerpo_asignacion($var2=null, $var1=null, $var3=null){


    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $var2;
    $year = $this->Session->read('ano_recurso_asignacion');
    $this->layout="ajax";
    $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);  $cond = "";
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));

     if(isset($_SESSION['consolidado_select_ventas_dependencia'])){if($_SESSION['consolidado_select_ventas_dependencia']==2){$Modulo="2";}}

     if($var1!=6){

					          if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
								 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.tipo_recurso=".$var1."  ";
								    $cod_dep_sql = " and x.tipo_recurso=".$var1." ";
								    $cod_dep_sql2 = "";
								    $this->set('global', 'si');
								}else{
									$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  and a.cod_dep_original = ".$this->cod_dep_consolidado()."  and a.tipo_recurso=".$var1."   ";
								    $cod_dep_sql  = "and x.cod_dep_original=".$this->cod_dep_consolidado()." and x.tipo_recurso=".$var1." ";
								    $cod_dep_sql2 = " , a.cod_dep";
								    $this->set('global', 'no');
								}//fin else


									 	$datos = $this->cfpd07_plan_inversion->execute("SELECT
														a.cod_presi,
														a.cod_entidad,
														a.cod_tipo_inst,
														a.cod_inst ".$cod_dep_sql2.",
														(SELECT SUM(estimado_presu)     FROM   cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." and status=1) as asignacion_inicial,
														(SELECT SUM(estimado_presu)     FROM   cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." and status=2) as credito_adicional,
														(SELECT SUM(estimado_presu)     FROM   cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." ) as total,
					                                    (SELECT SUM(aumento_obras)      FROM   cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year.") as aumento_obras,
					                                    (SELECT SUM(disminucion_obras)  FROM   cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year.") as disminucion_obras
														FROM
														       cfpd07_obras_cuerpo a
														WHERE
														  ".$cond." and  a.ano_estimacion    =    ".$year."
														group by
															a.cod_presi,
															a.cod_entidad,
															a.cod_tipo_inst,
															a.cod_inst ".$cod_dep_sql2."
														order by
															a.cod_presi,
															a.cod_entidad,
															a.cod_tipo_inst,
															a.cod_inst ".$cod_dep_sql2.";	");


     }else{




					     	    if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
								 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  ";
								    $cod_dep_sql = " ";
								    $cod_dep_sql2 = " ";
								    $this->set('global', 'si');
								}else{
									$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
								    $cod_dep_sql  = "and x.cod_dep_original=".$this->cod_dep_consolidado()."";
								    $cod_dep_sql2 = ", a.cod_dep";
								    $this->set('global', 'no');
								}//fin else


										$datos = $this->cfpd07_plan_inversion->execute("SELECT
														a.cod_presi,
														a.cod_entidad,
														a.cod_tipo_inst,
														a.cod_inst ".$cod_dep_sql2.",
														(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year." and status=1) as asignacion_inicial,
														(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year." and status=2) as credito_adicional,
														(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year.") as total,
					                                    (SELECT SUM(aumento_obras)      FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year.") as aumento_obras,
					                                    (SELECT SUM(disminucion_obras)  FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year.") as disminucion_obras

														   FROM
														       cfpd07_obras_cuerpo a
														WHERE
														  ".$cond." and  a.ano_estimacion    =    ".$year."
														group by
															a.cod_presi,
															a.cod_entidad,
															a.cod_tipo_inst,
															a.cod_inst ".$cod_dep_sql2."
														order by
															a.cod_presi,
															a.cod_entidad,
															a.cod_tipo_inst,
															a.cod_inst ".$cod_dep_sql2.";	");






     }//fin else


              if(!isset($datos[0][0]['total'])){
                   $datos[0][0]['asignacion_inicial'] = "";
                   $datos[0][0]['credito_adicional'] = "";
                   $datos[0][0]['total'] = "";
                   $datos[0][0]['disminucion_obras'] = "";
                   $datos[0][0]['aumento_obras'] = "";
              }//fin if

                 $this->set('asignacion_inicial', $datos[0][0]['asignacion_inicial']);
                 $this->set('credito_adicional', $datos[0][0]['credito_adicional']);
                 $this->set('total', ($datos[0][0]['total'] + $datos[0][0]['aumento_obras']) - $datos[0][0]['disminucion_obras']);

                 $this->set('year', $var2);
                 $this->set('tipo_recurso', $var1);
                 $this->set('clasificacion_recurso', $var3);





}//fin function














function cambiar_year($var2=null, $var1=null, $var3=null){


    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $var2;
    $year = $var2;
    $var1 = 6;
    $this->layout="ajax";
    $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);  $cond = "";
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));

     if(isset($_SESSION['consolidado_select_ventas_dependencia'])){if($_SESSION['consolidado_select_ventas_dependencia']==2){$Modulo="2";}}

     if($var1!=6){

					          if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
								 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.tipo_recurso=".$var1."  ";
								    $cod_dep_sql = " and x.tipo_recurso=".$var1." ";
								    $cod_dep_sql2 = "";
								    $this->set('global', 'si');
								}else{
									$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  and a.cod_dep_original = ".$this->cod_dep_consolidado()."  and a.tipo_recurso=".$var1."   ";
								    $cod_dep_sql  = "and x.cod_dep_original=".$this->cod_dep_consolidado()." and x.tipo_recurso=".$var1." ";
								    $cod_dep_sql2 = " , a.cod_dep";
								    $this->set('global', 'no');
								}//fin else


									 	$datos = $this->cfpd07_plan_inversion->execute("SELECT
														a.cod_presi,
														a.cod_entidad,
														a.cod_tipo_inst,
														a.cod_inst ".$cod_dep_sql2.",
														(SELECT SUM(estimado_presu)     FROM   cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." and status=1) as asignacion_inicial,
														(SELECT SUM(estimado_presu)     FROM   cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." and status=2) as credito_adicional,
														(SELECT SUM(estimado_presu)     FROM   cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." ) as total,
					                                    (SELECT SUM(aumento_obras)      FROM   cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year.") as aumento_obras,
					                                    (SELECT SUM(disminucion_obras)  FROM   cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year.") as disminucion_obras
														FROM
														       cfpd07_obras_cuerpo a
														WHERE
														  ".$cond." and  a.ano_estimacion    =    ".$year."
														group by
															a.cod_presi,
															a.cod_entidad,
															a.cod_tipo_inst,
															a.cod_inst ".$cod_dep_sql2."
														order by
															a.cod_presi,
															a.cod_entidad,
															a.cod_tipo_inst,
															a.cod_inst ".$cod_dep_sql2.";	");


     }else{




					     	    if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
								 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  ";
								    $cod_dep_sql = " ";
								    $cod_dep_sql2 = " ";
								    $this->set('global', 'si');
								}else{
									$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
								    $cod_dep_sql  = "and x.cod_dep_original=".$this->cod_dep_consolidado()."";
								    $cod_dep_sql2 = ", a.cod_dep";
								    $this->set('global', 'no');
								}//fin else


										$datos = $this->cfpd07_plan_inversion->execute("SELECT
														a.cod_presi,
														a.cod_entidad,
														a.cod_tipo_inst,
														a.cod_inst ".$cod_dep_sql2.",
														(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year." and status=1) as asignacion_inicial,
														(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year." and status=2) as credito_adicional,
														(SELECT SUM(estimado_presu) FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year.") as total,
					                                    (SELECT SUM(aumento_obras)      FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year.") as aumento_obras,
					                                    (SELECT SUM(disminucion_obras)  FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year.") as disminucion_obras

														   FROM
														       cfpd07_obras_cuerpo a
														WHERE
														  ".$cond." and  a.ano_estimacion    =    ".$year."
														group by
															a.cod_presi,
															a.cod_entidad,
															a.cod_tipo_inst,
															a.cod_inst ".$cod_dep_sql2."
														order by
															a.cod_presi,
															a.cod_entidad,
															a.cod_tipo_inst,
															a.cod_inst ".$cod_dep_sql2.";	");






     }//fin else


              if(!isset($datos[0][0]['total'])){
                   $datos[0][0]['asignacion_inicial'] = "";
                   $datos[0][0]['credito_adicional'] = "";
                   $datos[0][0]['total'] = "";
                   $datos[0][0]['disminucion_obras'] = "";
                   $datos[0][0]['aumento_obras'] = "";
              }//fin if

                 $this->set('asignacion_inicial', $datos[0][0]['asignacion_inicial']);
                 $this->set('credito_adicional', $datos[0][0]['credito_adicional']);
                 $this->set('total', ($datos[0][0]['total'] + $datos[0][0]['aumento_obras']) - $datos[0][0]['disminucion_obras']);

                 $this->set('year', $var2);
                 $this->set('tipo_recurso', $var1);
                 $this->set('clasificacion_recurso', $var3);
}//fin function










function cargar_dep($var=null){

    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$ano = $this->ano_ejecucion();
	$condicion = "arrd05.cod_presi = ".$cod_presi." and arrd05.cod_entidad = ".$cod_entidad." and arrd05.cod_tipo_inst = ".$cod_tipo_inst." and arrd05.cod_inst = ".$cod_inst;

   $this->Session->write('select_dependencia',$var);

    echo "<script>
	     if(document.getElementById('tipo_recurso')){
                  document.getElementById('tipo_recurso_1').checked = false;
                  document.getElementById('tipo_recurso_2').checked = false;
                  document.getElementById('tipo_recurso_3').checked = false;
                  document.getElementById('tipo_recurso_4').checked = false;
                  document.getElementById('tipo_recurso_5').checked = false;
                  document.getElementById('tipo_recurso_6').checked = false;
		 }//fin fi
   </script>";



}//fin function



























function contratado_pagado($opcion=null){



	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);

      $SScoddeporig             =       $this->Session->read('SScoddeporig');
	  $SScoddep                 =       $this->Session->read('SScoddep');
	  $Modulo                   =       $this->Session->read('Modulo');
	  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
	  $lista = "";

      $vara = 0;
      $varb = 0;



	if(isset($this->data['cfpp05']['consolidacion'])){if($this->data['cfpp05']['consolidacion']==2){$Modulo="2";}}


	if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
	 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";
	    $this->set('global', 'si');
	}else{
		 $cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
	    $this->set('global', 'no');
	}//fin else



        $this->set('opcion', $opcion);
        $this->set('year', $ano);

	     if($opcion=='si'){
		   $this->layout="ajax";

    				$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
    					 a.cod_obra,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras,
                         (SELECT SUM(monto_anticipo)     FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_anticipo,
	                     (SELECT SUM(monto_cancelado)    FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_cancelado,
	                     (SELECT SUM(monto_amortizacion) FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_amortizacion,
                         (SELECT SUM(monto_contratado)   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_obra=a.cod_obra and x.ano_estimacion=a.ano_estimacion) as monto_contratado

				 		      FROM cfpd07_obras_cuerpo a where ".$cond." and  a.ano_estimacion  =   ".$ano."

                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_estimacion, a.cod_obra
    					 ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst DESC;");



                             $datoss[0][0]['monto_contratado']    = 0;
                             $datoss[0][0]['monto_anticipo']      = 0;
                             $datoss[0][0]['monto_cancelado']     = 0;
                             $datoss[0][0]['monto_amortizacion']  = 0;

    			  foreach($datos as $vea){
                             $datoss[0][0]['monto_contratado'] += $vea[0]['monto_contratado'];
                             $datoss[0][0]['monto_anticipo'] += $vea[0]['monto_anticipo'];
                             $datoss[0][0]['monto_cancelado'] += $vea[0]['monto_cancelado'];
                             $datoss[0][0]['monto_amortizacion'] += $vea[0]['monto_amortizacion'];

    			  }//fin foreach


                             $datos[0][0]['monto_contratado']   = $datoss[0][0]['monto_contratado'];
                             $datos[0][0]['monto_anticipo']     = $datoss[0][0]['monto_anticipo'];
                             $datos[0][0]['monto_cancelado']    = $datoss[0][0]['monto_cancelado'];
                             $datos[0][0]['monto_amortizacion'] = $datoss[0][0]['monto_amortizacion'];

				 $vara = ($datos[0][0]['monto_contratado']);
				 $varb = ($datos[0][0]['monto_anticipo']    + $datos[0][0]['monto_cancelado']) -  $datos[0][0]['monto_amortizacion'];
                 $this->set('total_presupuestado',   ($datos[0][0]['monto_contratado']));
                 $this->set('monto_contratado',      ($datos[0][0]['monto_anticipo']    + $datos[0][0]['monto_cancelado']) -  $datos[0][0]['monto_amortizacion']);
                 $this->set('diferencia',             $vara - $varb);


	}else{
		   $this->layout = "pdf";
		   $username = $this->Session->read('nom_usuario');
			$this->set('user', $username);
			$total_presupuestado       =   $this->data["graficos1"]["total_presupuestado"];
			$monto_contratado          =   $this->data["graficos1"]["monto_contratado"];
			$diferencia                =   $this->data["graficos1"]["diferencia"];
			$por_monto_contratado      =   $this->data["graficos1"]["por_monto_contratado"];
			$por_total_presupuestado   =   $this->data["graficos1"]["por_total_presupuestado"];
			$por_diferencia            =   $this->data["graficos1"]["por_diferencia"];
			$rdm                       =   $this->data["graficos1"]["rdm"];
			$year                      =   $this->data["graficos1"]["year"];
			$tipo_recurso              =   $this->data["graficos1"]["tipo_recurso"];
			$clasificacion_recurso     =   $this->data["graficos1"]["clasificacion_recurso"];

            $this->set('year', $year);
                 if($clasificacion_recurso=='TODO' || $clasificacion_recurso==''){
                 	           $datos[0][0]['denominacion']="";
                 }else{
                   	  $datos = $this->cfpd07_plan_inversion->execute("SELECT
								  a.cod_presi,
								  a.cod_entidad,
								  a.cod_tipo_inst,
								  a.cod_inst,
								  a.ano_recurso,
								  a.tipo_recurso,
								  a.clasificacion_recurso,
								  b.denominacion,
								  a.asignacion_total,
								  a.monto_presupuestado


								FROM cfpd07_plan_inversion a ,  cfpd07_clasificacion_recurso b  where


								    a.cod_presi                =   ".$cod_presi." and
								    a.cod_entidad              =   ".$cod_entidad." and
								    a.cod_tipo_inst            =   ".$cod_tipo_inst." and
								    a.cod_inst                 =   ".$cod_inst." and
								    b.cod_presi                =     a.cod_presi and
								    b.cod_entidad              =     a.cod_entidad and
								    b.cod_tipo_inst            =     a.cod_tipo_inst and
								    b.cod_inst                 =     a.cod_inst and
								    a.tipo_recurso             =   ".$tipo_recurso." and
								    a.ano_recurso              =   ".$year." and
								    a.clasificacion_recurso    =   ".$clasificacion_recurso." and
								    a.tipo_recurso             =   b.tipo_recurso and
								    a.clasificacion_recurso    =   b.clasificacion_recurso


								ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.tipo_recurso DESC;");

                            $datos[0][0]['denominacion'] = ' / '.$datos[0][0]['denominacion'];
                         }//fin else

            $tipo_recurso++;
            $opcion1_aux = "";
         if($tipo_recurso==1){  $opcion1_aux = "";
   }else if($tipo_recurso==2){  $opcion1_aux = "Ordinario";
   }else if($tipo_recurso==3){  $opcion1_aux = "Coordinado";
   }else if($tipo_recurso==4){  $opcion1_aux = "Laee";
   }else if($tipo_recurso==5){  $opcion1_aux = "Fides";
   	}else if($tipo_recurso==6){ $opcion1_aux = "Ingreso Extraordinario";
   }else if($tipo_recurso==9){  $opcion1_aux = ""; }//fin else

            $this->set('opcion1_aux', $opcion1_aux);
            $this->set('opcion2_aux', $datos[0][0]['denominacion']);
            $this->set('tipo_recurso', $tipo_recurso);
            $this->set('clasificacion_recurso', $clasificacion_recurso);
			$this->set('total_presupuestado', $total_presupuestado);
			$this->set('monto_contratado', $monto_contratado);
			$this->set('diferencia', $diferencia);
			$this->set('por_monto_contratado', $por_monto_contratado);
			$this->set('por_total_presupuestado', $por_total_presupuestado);
			$this->set('por_diferencia', $por_diferencia);
			$this->set('rdm', $rdm);


         }//fin function

$this->set('titulo_inst', $this->Session->read('entidad_federal'));
$this->set('titulo_a',$this->Session->read('dependencia'));


}//fin function






function cuerpo_pagado($var2=null, $var1=null, $var3=null){


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $this->layout="ajax";
    $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);  $cond = "";
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));

      $SScoddeporig             =       $this->Session->read('SScoddeporig');
	  $SScoddep                 =       $this->Session->read('SScoddep');
	  $Modulo                   =       $this->Session->read('Modulo');
	  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
	  $lista = "";



	if(isset($_SESSION['consolidado_select_ventas_dependencia'])){if($_SESSION['consolidado_select_ventas_dependencia']==2){$Modulo="2";}}

	if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
	 	$cond = " ";
	    $this->set('global', 'si');
	}else{
		$cond = "  and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
	    $this->set('global', 'no');
	}//fin else


    if($var3!='todo' && $var3!=null){
                $cond .= " and a.clasificacion_recurso   =   ".$var3." ";
    }//fin else


     if($var1!=6){


    					$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
    					 a.cod_obra,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras,
				 	     (SELECT SUM(monto_anticipo)     FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_anticipo,
	                     (SELECT SUM(monto_cancelado)    FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_cancelado,
	                     (SELECT SUM(monto_amortizacion) FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_amortizacion,
                         (SELECT SUM(monto_contratado)   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst       and x.ano_estimacion=a.ano_estimacion  and x.cod_obra=a.cod_obra) as monto_contratado


				 		      FROM cfpd07_obras_cuerpo a where
				 		      		a.cod_presi             =   ".$cod_presi." and
								    a.cod_entidad           =   ".$cod_entidad." and
								    a.cod_tipo_inst         =   ".$cod_tipo_inst." and
								    a.cod_inst              =   ".$cod_inst." and
								    a.tipo_recurso          =   ".$var1.$cond." and
							 		a.ano_estimacion        =   ".$var2."

                        GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst,  a.ano_estimacion, a.cod_obra
    					ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst DESC;");




     }else{


    					$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
    					 a.cod_obra,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras,
				 	     (SELECT SUM(monto_anticipo)     FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_anticipo,
	                     (SELECT SUM(monto_cancelado)    FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_cancelado,
	                     (SELECT SUM(monto_amortizacion) FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_amortizacion,
                         (SELECT SUM(monto_contratado)   FROM  cfpd07_obras_cuerpo         x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra) as monto_contratado


				 		      FROM cfpd07_obras_cuerpo a where
				 		      		a.cod_presi             =   ".$cod_presi." and
								    a.cod_entidad           =   ".$cod_entidad." and
								    a.cod_tipo_inst         =   ".$cod_tipo_inst." and
								    a.cod_inst              =   ".$cod_inst.$cond." and
							 		a.ano_estimacion        =   ".$var2."

                        GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst,  a.ano_estimacion, a.cod_obra
    					ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst DESC;");




     }//fin else

                            $datoss[0][0]['monto_contratado']    = 0;
                             $datoss[0][0]['monto_anticipo']      = 0;
                             $datoss[0][0]['monto_cancelado']     = 0;
                             $datoss[0][0]['monto_amortizacion']  = 0;

    			  foreach($datos as $vea){
                             $datoss[0][0]['monto_contratado'] += $vea[0]['monto_contratado'];
                             $datoss[0][0]['monto_anticipo'] += $vea[0]['monto_anticipo'];
                             $datoss[0][0]['monto_cancelado'] += $vea[0]['monto_cancelado'];
                             $datoss[0][0]['monto_amortizacion'] += $vea[0]['monto_amortizacion'];

    			  }//fin foreach


                             $datos[0][0]['monto_contratado']   = $datoss[0][0]['monto_contratado'];
                             $datos[0][0]['monto_anticipo']     = $datoss[0][0]['monto_anticipo'];
                             $datos[0][0]['monto_cancelado']    = $datoss[0][0]['monto_cancelado'];
                             $datos[0][0]['monto_amortizacion'] = $datoss[0][0]['monto_amortizacion'];

				 $vara = ($datos[0][0]['monto_contratado']);
				 $varb = ($datos[0][0]['monto_anticipo']    + $datos[0][0]['monto_cancelado']) -  $datos[0][0]['monto_amortizacion'];
                 $this->set('total_presupuestado',   ($datos[0][0]['monto_contratado']));
                 $this->set('monto_contratado',      ($datos[0][0]['monto_anticipo']    + $datos[0][0]['monto_cancelado']) -  $datos[0][0]['monto_amortizacion']);
                 $this->set('diferencia',             $vara - $varb);


                 $this->set('year', $var2);
                 $this->set('tipo_recurso', $var1);
                 $this->set('clasificacion_recurso', $var3);





}//fin function










function cambiar_year_cuerpo_pagado($var2=null, $var1=null, $var3=null){


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $var2;
    $var1 = 6;
    $this->layout="ajax";
    $username = $this->Session->read('nom_usuario');
    $rdm = mt_rand();
    $this->set('username', $username);
    $this->set('rdm', $rdm);  $cond = "";
    $this->set('titulo_inst', $this->Session->read('entidad_federal'));
    $this->set('titulo_a',$this->Session->read('dependencia'));

      $SScoddeporig             =       $this->Session->read('SScoddeporig');
	  $SScoddep                 =       $this->Session->read('SScoddep');
	  $Modulo                   =       $this->Session->read('Modulo');
	  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
	  $lista = "";



	if(isset($_SESSION['consolidado_select_ventas_dependencia'])){if($_SESSION['consolidado_select_ventas_dependencia']==2){$Modulo="2";}}

	if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
	 	$cond = " ";
	    $this->set('global', 'si');
	}else{
		$cond = "  and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
	    $this->set('global', 'no');
	}//fin else


    if($var3!='todo' && $var3!=null){
                $cond .= " and a.clasificacion_recurso   =   ".$var3." ";
    }//fin else


     if($var1!=6){


    					$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
    					 a.cod_obra,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras,
				 	     (SELECT SUM(monto_anticipo)     FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_anticipo,
	                     (SELECT SUM(monto_cancelado)    FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_cancelado,
	                     (SELECT SUM(monto_amortizacion) FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_amortizacion,
                         (SELECT SUM(monto_contratado)   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst       and x.ano_estimacion=a.ano_estimacion  and x.cod_obra=a.cod_obra) as monto_contratado


				 		      FROM cfpd07_obras_cuerpo a where
				 		      		a.cod_presi             =   ".$cod_presi." and
								    a.cod_entidad           =   ".$cod_entidad." and
								    a.cod_tipo_inst         =   ".$cod_tipo_inst." and
								    a.cod_inst              =   ".$cod_inst." and
								    a.tipo_recurso          =   ".$var1.$cond." and
							 		a.ano_estimacion        =   ".$var2."

                        GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst,  a.ano_estimacion, a.cod_obra
    					ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst DESC;");




     }else{


    					$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
    					 a.cod_obra,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras,
				 	     (SELECT SUM(monto_anticipo)     FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_anticipo,
	                     (SELECT SUM(monto_cancelado)    FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_cancelado,
	                     (SELECT SUM(monto_amortizacion) FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_amortizacion,
                         (SELECT SUM(monto_contratado)   FROM  cfpd07_obras_cuerpo         x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion and x.cod_obra=a.cod_obra) as monto_contratado


				 		      FROM cfpd07_obras_cuerpo a where
				 		      		a.cod_presi             =   ".$cod_presi." and
								    a.cod_entidad           =   ".$cod_entidad." and
								    a.cod_tipo_inst         =   ".$cod_tipo_inst." and
								    a.cod_inst              =   ".$cod_inst.$cond." and
							 		a.ano_estimacion        =   ".$var2."

                        GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst,  a.ano_estimacion, a.cod_obra
    					ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst DESC;");




     }//fin else

                            $datoss[0][0]['monto_contratado']    = 0;
                             $datoss[0][0]['monto_anticipo']      = 0;
                             $datoss[0][0]['monto_cancelado']     = 0;
                             $datoss[0][0]['monto_amortizacion']  = 0;

    			  foreach($datos as $vea){
                             $datoss[0][0]['monto_contratado'] += $vea[0]['monto_contratado'];
                             $datoss[0][0]['monto_anticipo'] += $vea[0]['monto_anticipo'];
                             $datoss[0][0]['monto_cancelado'] += $vea[0]['monto_cancelado'];
                             $datoss[0][0]['monto_amortizacion'] += $vea[0]['monto_amortizacion'];

    			  }//fin foreach


                             $datos[0][0]['monto_contratado']   = $datoss[0][0]['monto_contratado'];
                             $datos[0][0]['monto_anticipo']     = $datoss[0][0]['monto_anticipo'];
                             $datos[0][0]['monto_cancelado']    = $datoss[0][0]['monto_cancelado'];
                             $datos[0][0]['monto_amortizacion'] = $datoss[0][0]['monto_amortizacion'];

				 $vara = ($datos[0][0]['monto_contratado']);
				 $varb = ($datos[0][0]['monto_anticipo']    + $datos[0][0]['monto_cancelado']) -  $datos[0][0]['monto_amortizacion'];
                 $this->set('total_presupuestado',   ($datos[0][0]['monto_contratado']));
                 $this->set('monto_contratado',      ($datos[0][0]['monto_anticipo']    + $datos[0][0]['monto_cancelado']) -  $datos[0][0]['monto_amortizacion']);
                 $this->set('diferencia',             $vara - $varb);


                 $this->set('year', $var2);
                 $this->set('tipo_recurso', $var1);
                 $this->set('clasificacion_recurso', $var3);





}//fin function





}//fin class
?>